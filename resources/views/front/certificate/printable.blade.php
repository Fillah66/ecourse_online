<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certificate {{ $data->user->name }} {{ $data->course->name }}</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .certificate {
            width: 297mm;
            height: 210mm;
            padding: 10mm 10mm;
            position: relative;
            overflow: hidden;
        }

        /* WATERMARK (W3 STYLE) */
        .watermark {
            position: absolute;
            left: 50%;
            top: 20%;
            transform: translateX(-50%);
            width: 80mm;
            opacity: 0.07;
            z-index: 0;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .logo {
            width: 50mm;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        /* TITLE */
        .title {
            text-align: center;
            font-size: 26pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 18mm;
            position: relative;
            z-index: 2;
        }

        .subtitle {
            text-align: center;
            font-size: 12pt;
            margin-top: 10mm;
        }

        /* NAME */
        .name {
            text-align: center;
            font-size: 22pt;
            font-weight: bold;
            margin: 10mm 0;
        }

        /* DESCRIPTION */
        .desc {
            text-align: center;
            font-size: 11.5pt;
            max-width: 175mm;
            margin: auto;
            line-height: 1.5;
        }

        /* COURSE */
        .course {
            text-align: center;
            font-size: 19pt;
            font-weight: bold;
            margin: 12mm 0;
        }

        /* BADGE */
        .badge {
            text-align: center;
            margin: 10mm 0;
        }

        .badge img {
            width: 32mm;
        }

        /* LEVEL & DATE */
        .level {
            text-align: center;
            font-size: 10.5pt;
            margin-top: 6mm;
        }

        .date {
            text-align: center;
            font-size: 10pt;
            margin-top: 6mm;
        }

        /* FOOTER */
        .footer {
            position: absolute;
            bottom: 35mm;
            left: 30mm;
            right: 30mm;
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
        }

        .signature {
            text-align: center;
        }

        .signature img {
            width: 48mm;
        }

        .signature p {
            font-size: 10pt;
            margin-top: 1mm;
        }

        .signature span {
            font-size: 9pt;
        }
    </style>
</head>

<body>

    <div class="certificate">

        <!-- WATERMARK -->
        <img src="{{ public_path('assets/logo/logo1.png') }}" class="watermark">

        <!-- HEADER -->
        <div class="header">
            <img src="{{ public_path('assets/logo/logo.png') }}" class="logo">
        </div>

        <!-- CONTENT -->
        <h1 class="title">CERTIFICATE OF COMPLETION</h1>

        <p class="subtitle">This certifies that</p>

        <h2 class="name">{{ $data->user->name }}</h2>

        <p class="desc">
            has passed the Nayaguna {{ $data->course->name }} exam and is hereby declared a
        </p>

        <h2 class="course">Certified {{ $data->course->name }}</h2>

        <p class="level">
            The candidate has passed the exam at the Advanced level.
        </p>

        <p class="date">
            Issued on {{ \Carbon\Carbon::parse($data->created_at)->format('F d, Y') }}
        </p>

        <!-- FOOTER -->
        <div class="footer">
            <div class="signature">
                <img src="{{ public_path('assets/logo/signature.png') }}">
                <p>
                    Eva Susilawati, S.Kom., MM<br>
                    <span>CEO Nayaguna Tech</span>
                </p>
            </div>
        </div>

    </div>

</body>

</html>
