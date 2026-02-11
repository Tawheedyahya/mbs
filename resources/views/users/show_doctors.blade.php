<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Doctors</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 50px;
            color: white;
        }

        .page-header h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .page-header p {
            font-size: 18px;
            opacity: 0.9;
        }

        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .doctor-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .doctor-card:hover {
            transform: translateY(-6px);
        }

        .doctor-image {
            width: 100%;
            height: 230px;
            background: #f1f3f5;
            overflow: hidden;
        }

        .doctor-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .doctor-info {
            padding: 22px;
        }

        .doctor-name {
            font-size: 22px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 6px;
        }

        .specialization {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .qualification {
            color: #718096;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .experience-years {
            display: inline-block;
            background: #e6fffa;
            color: #319795;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .description {
            color: #4a5568;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        /* Booking UX */
        .booking-form {
            margin-top: 10px;
        }

        .date-input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px dashed #bbb;
            font-size: 14px;
            background: #f8f9fa;
            cursor: pointer;
            margin-bottom: 12px;
        }

        .hidden {
            display: none;
        }

        .book-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .book-btn:hover {
            opacity: 0.95;
        }

        @media (max-width: 768px) {
            .doctors-grid {
                grid-template-columns: 1fr;
            }
        }

        .danger-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 300px;
            max-width: 420px;
            padding: 14px 18px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: #fff;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 500;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            z-index: 1080;

            animation: danger-toast-in 0.4s ease forwards;
        }

        /* Animation */
        @keyframes danger-toast-in {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        @if (session('error'))
            <div class="danger-toast">
                <strong>❌ Error</strong> Doctors not available on this day
            </div>
        @endif

        <header class="page-header">
            <h1>Our Doctors</h1>
            <p>Choose your doctor and appointment date</p>
        </header>

        <div class="doctors-grid">

            @foreach ($doctor as $doc)
                <div class="doctor-card">

                    <div class="doctor-image">
                        <img src="{{ Storage::disk('s3')->url($doc->profile_photo) }}" alt="{{ $doc->name }}"
                            loading="lazy">
                    </div>

                    <div class="doctor-info">

                        <h3 class="doctor-name">{{ $doc->name }}</h3>

                        <span class="specialization">
                            {{ $doc->specialization }}
                        </span>

                        <p class="qualification">
                            {{ $doc->qualification }}
                        </p>

                        <span class="experience-years">
                            {{ $doc->experience_years }} Years Experience
                        </span>

                        <p class="description">
                            {{ $doc->description }}
                        </p>
                        <?php
                        $phone_no=$_GET['phone_no']??null;
                        ?>
                        <!-- BOOKING FORM -->
                        <form method="GET" action="{{ route('patient.booking', $doc->id) }}" class="booking-form">
                            @php
                                // Get today's date and calculate the range
                                $today = now();
                                $nextWeek = now()->addDays(7);
                            @endphp
                            <input type="date" name="date" class="date-input hidden"
                                     min="{{ $today->toDateString() }}"
       max="{{ $nextWeek->toDateString() }}"
       value="{{ $today->toDateString() }}"  required>
        <input type="hidden" name="phone_no" value="{{ $phone_no }}">

                            <button type="button" class="book-btn" onclick="handleBookingClick(this)">
                                Book Appointment
                            </button>
                        </form>

                    </div>
                </div>
            @endforeach

        </div>

    </div>

    <!-- Minimal JS -->
    <script>
        function handleBookingClick(button) {
            const form = button.closest('form');
            const dateInput = form.querySelector('.date-input');

            // First click: show date picker
            if (dateInput.classList.contains('hidden')) {
                dateInput.classList.remove('hidden');
                dateInput.focus();
                button.innerText = 'Confirm Date';
                return;
            }

            // Second click: submit form
            form.submit();
        }
    </script>
    <script>
        setTimeout(() => {
            const toast = document.querySelector('.danger-toast');
            if (toast) toast.remove();
        }, 4000);
    </script>

</body>

</html>
