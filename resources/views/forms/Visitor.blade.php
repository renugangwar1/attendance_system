@extends('layouts.forms')

@section('title', 'Visitor Form')

@section('content')
<div class="container mt-5 animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg rounded-4 border-0">
                <div class="card-header bg-gradient-primary text-black text-center rounded-top-4">
                    <h4 class="mb-0">Visitor Form</h4>
                </div>
                {{-- Show success message if form was submitted --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- Only show form if not submitted --}}
                @unless(session('success'))
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('form.visitor.submit') }}" enctype="multipart/form-data">

                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mobile No</label>
                            <input type="tel" name="mobile" class="form-control" maxlength="10" pattern="[0-9]{10}"
                                required>
                        </div>



                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" rows="3" class="form-control"></textarea>
                        </div>
                        @extends('layouts.forms')

                        @section('title', 'Visitor Form')

                        @section('content')
                        <div class="container mt-5 animate__animated animate__fadeIn">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card shadow-lg rounded-4 border-0">
                                        <div
                                            class="card-header bg-gradient-primary text-black text-center rounded-top-4">
                                            <h4 class="mb-0">Visitor Form</h4>
                                        </div>

                                        {{-- Show success message if form was submitted --}}
                                        @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        @endif

                                        {{-- Only show form if not submitted --}}
                                        @unless(session('success'))
                                        <div class="card-body p-4">
                                            <form method="POST" action="{{ route('form.visitor.submit') }}"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Mobile No</label>
                                                    <input type="tel" name="mobile" class="form-control" maxlength="10"
                                                        pattern="[0-9]{10}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Address</label>
                                                    <textarea name="address" rows="3" class="form-control"></textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Capture Image</label><br>
                                                    <video id="video" width="100%" height="300" autoplay
                                                        class="rounded border mb-2"></video>
                                                    <canvas id="canvas" width="640" height="480"
                                                        style="display:none;"></canvas>
                                                    <input type="hidden" name="image_data" id="image_data">
                                                    <button type="button" id="capture"
                                                        class="btn btn-sm btn-secondary mb-2">📸 Capture</button>
                                                    <div>
                                                        <img id="preview" src="" style="max-width:100%; display:none;"
                                                            class="img-thumbnail">
                                                    </div>
                                                    <div id="cameraError" class="text-danger mt-2"
                                                        style="display:none;">
                                                        Could not access camera. Please allow camera permissions or use
                                                        a supported device.
                                                    </div>
                                                </div>

                                                <div class="text-center">
                                                    <button type="submit"
                                                        class="btn btn-gradient-primary px-4">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- JavaScript --}}
                                    <script>
                                    const video = document.getElementById('video');
                                    const canvas = document.getElementById('canvas');
                                    const image_data = document.getElementById('image_data');
                                    const preview = document.getElementById('preview');

                                    navigator.mediaDevices.getUserMedia({
                                            video: {
                                                facingMode: "environment"
                                            }
                                        })
                                        .then(stream => {
                                            video.srcObject = stream;
                                        })
                                        .catch(err => {
                                            console.error("Camera access error: ", err);
                                            document.getElementById('cameraError').style.display = 'block';
                                        });

                                    document.getElementById('capture').addEventListener('click', () => {
                                        const context = canvas.getContext('2d');
                                        canvas.width = video.videoWidth;
                                        canvas.height = video.videoHeight;
                                        context.drawImage(video, 0, 0, canvas.width, canvas.height);

                                        const dataUrl = canvas.toDataURL('image/jpeg');
                                        image_data.value = dataUrl;
                                        preview.src = dataUrl;
                                        preview.style.display = 'block';
                                    });
                                    </script>
                                </div>
                            </div>
                        </div>
                        @endsection