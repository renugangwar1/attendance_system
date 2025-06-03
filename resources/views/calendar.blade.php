<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Calendar Guide</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.css" rel="stylesheet" />

    <style>
    /* Page background and layout */
    body {
        background: #f0f4f8;
        font-family: 'Arial', sans-serif;
        padding: 0;
        margin: 0;
    }

    /* Container for the calendar */
    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Calendar title */
    h1 {
        text-align: center;
        color: #333;
        font-size: 36px;
        margin-bottom: 30px;
        font-weight: bold;
    }

    /* Styling for the calendar */
    #calendar {
        background-color: #fff;
        padding: 20px;
        min-height: 600px;
        border: 1px solid #ddd;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Style for event titles */
    .fc-event {
        background-color: #6c5ce7;
        color: #fff;
        border-radius: 8px;
        padding: 5px;
        font-size: 14px;
        text-align: center;
    }

    /* Hover effect for events */
    .fc-event:hover {
        background-color: #a29bfe;
        cursor: pointer;
    }

    /* FullCalendar header */
    .fc-toolbar {
        background-color: #6c5ce7;
        color: white;
        border-radius: 8px;
        padding: 10px;
    }

    .fc-toolbar .fc-button {
        background-color: #a29bfe;
        color: white;
        border: none;
    }

    .fc-toolbar .fc-button:hover {
        background-color: #6c5ce7;
    }
    </style>
</head>

<body>

    <div class="container mt-4">
        <h1 class="mb-4">📅 Calendar </h1>




        <div id="calendar"></div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            events: [{
                    title: 'Midterm Exams',
                    start: '2025-04-20'
                },
                {
                    title: 'Holiday: Independence Day',
                    start: '2025-08-15'
                },
                {
                    title: 'New Session Begins',
                    start: '2025-07-01'
                }
            ]
        });
    });
    </script>

</body>

</html>