<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Attendance</title>
    <style>
    #videoInput {
        border: 1px solid black;
    }

    #status {
        margin-top: 10px;
        font-size: 1.2em;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <h1>Real-Time Attendance</h1>

    <!-- Video Feed for Real-Time Face Recognition -->
    <video id="videoInput" width="720" height="560" autoplay muted></video>
    <div id="status">Detecting...</div>

    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
    <script>
    const video = document.getElementById('videoInput');
    const status = document.getElementById('status');

    async function startVideo() {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: {}
        });
        video.srcObject = stream;
    }

    // Load the face-api models
    Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('/models')
    ]).then(startVideo);

    // Fetch stored face descriptors from the backend
    let labeledDescriptors = [];

    async function loadLabeledDescriptors() {
        const response = await fetch("{{ route('attendance.getDescriptors') }}");
        const data = await response.json();
        labeledDescriptors = data.map(person => {
            return new faceapi.LabeledFaceDescriptors(
                person.label,
                [new Float32Array(person.descriptor)]
            );
        });
    }

    // Detect faces and compare descriptors
    video.addEventListener('play', async () => {
        await loadLabeledDescriptors();
        const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6);

        const canvas = faceapi.createCanvasFromMedia(video);
        document.body.append(canvas);
        const displaySize = {
            width: video.width,
            height: video.height
        };
        faceapi.matchDimensions(canvas, displaySize);

        setInterval(async () => {
            const detections = await faceapi.detectAllFaces(video, new faceapi
                .TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();
            const resizedDetections = faceapi.resizeResults(detections, displaySize);
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

            const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));
            results.forEach((result, i) => {
                const box = resizedDetections[i].detection.box;
                const drawBox = new faceapi.draw.DrawBox(box, {
                    label: result.toString()
                });
                drawBox.draw(canvas);

                if (result.label !== 'unknown') {
                    status.innerText = `Matched: ${result.label}`;
                    markAttendance(result.label);
                }
            });
        }, 2000);
    });

    // Mark attendance via AJAX
    function markAttendance(label) {
        fetch("{{ route('attendance.mark') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    label
                })
            })
            .then(res => res.json())
            .then(data => console.log("Attendance marked", data))
            .catch(err => console.error("Error marking attendance", err));
    }
    </script>
</body>

</html>