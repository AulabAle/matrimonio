<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Invito di Matrimonio - Monica & Erasmo</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Pinyon+Script&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        body {
            font-family: 'Cormorant Garamond', 'Georgia', serif;
            color: #2D3130;
            margin: 0;
            padding: 0;
            background-color: #FAF6F0; /* Elegant light ivory */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            position: relative;
            height: 297mm;
            width: 210mm;
            text-align: center;
        }
        .watermark {
            position: absolute;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
            opacity: 0.08;
            z-index: 0;
        }
        .watermark img {
            width: 100%;
            height: 100%;
        }
        .content {
            position: relative;
            z-index: 10;
            text-align: center;
            box-sizing: border-box;
            padding: 25mm 20mm 20mm 20mm;
        }
        .names {
            font-family: 'Pinyon Script', cursive;
            font-size: 38pt;
            color: #2D3130;
            margin-bottom: 25pt;
            line-height: 1.1;
            text-align: center;
        }
        .ampersand {
            font-family: 'Cormorant Garamond', 'Baskerville', Georgia, serif;
            font-style: italic;
            font-size: 24pt;
            color: #D1B280;
            margin: 8pt 0;
            line-height: 1;
            text-align: center;
        }
        .announcement {
            font-family: 'Pinyon Script', cursive;
            font-size: 22pt;
            color: #2D3130;
            margin-bottom: 10pt;
            line-height: 1.3;
            text-align: center;
        }
        .celebrated {
            font-family: 'Pinyon Script', cursive;
            font-size: 22pt;
            color: #2D3130;
            margin-bottom: 25pt;
            line-height: 1.3;
            text-align: center;
        }
        .venue-title {
            font-family: 'Pinyon Script', cursive;
            font-size: 24pt;
            color: #2D3130;
            margin-top: 15pt;
            margin-bottom: 5pt;
            line-height: 1.3;
            text-align: center;
        }
        .venue-address {
            font-family: 'Pinyon Script', cursive;
            font-size: 18pt;
            color: #2D3130;
            margin-bottom: 20pt;
            line-height: 1.3;
            text-align: center;
        }
        .reception-intro {
            font-family: 'Pinyon Script', cursive;
            font-size: 22pt;
            color: #2D3130;
            margin-top: 25pt;
            margin-bottom: 10pt;
            line-height: 1.3;
            text-align: center;
        }
        .rsvp-text {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: 13pt;
            color: #5C605F;
            margin-top: 40pt;
            text-align: center;
        }
        .footer-addresses {
            width: 170mm;
            position: absolute;
            bottom: 40mm;
            left: 20mm;
            border-collapse: collapse;
        }
        .address-left {
            width: 50%;
            text-align: left;
            font-family: 'Pinyon Script', cursive;
            font-size: 18pt;
            color: #2D3130;
            line-height: 1.4;
        }
        .address-right {
            width: 50%;
            text-align: right;
            font-family: 'Pinyon Script', cursive;
            font-size: 18pt;
            color: #2D3130;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <!-- Watermark Background -->
    @if(file_exists(public_path('images/bg-sposi-processed.png')))
    <div class="watermark">
        <img src="{{ public_path('images/bg-sposi-processed.png') }}" alt="Sposi Watermark">
    </div>
    @endif

    <div class="content">
        <!-- Names -->
        <div class="names">
            <div>Monica Amendolara</div>
            <div class="ampersand">&amp;</div>
            <div>Erasmo Porfido</div>
        </div>
        
        <!-- Announcement -->
        <div class="announcement">
            annunciano il loro matrimonio
        </div>
        
        <!-- Celebration Clause -->
        <div class="celebrated">
            che sarà celebrato
        </div>
        
        <!-- Date and Time -->
        <div class="venue-title" style="font-size: 26pt; margin-bottom: 15pt;">
            Venerdì 9 Ottobre 2026 alle ore 11:00
        </div>
        
        <!-- Ceremony Location -->
        <div class="venue-title">
            presso la Parrocchia «Gesù Buon Pastore»
        </div>
        <div class="venue-address">
            Via Guardialto, 76 - Gravina
        </div>
        
        <!-- Reception Location -->
        <div class="reception-intro">
            e dopo la cerimonia saranno lieti di festeggiare presso
        </div>
        <div class="venue-title">
            «Masseria del Parco»
        </div>
        <div class="venue-address">
            S.P. 8 Matera-Grassano, Km 5.420 - Matera
        </div>
        
        <!-- RSVP Text -->
        <div class="rsvp-text">
            È gradita gentile conferma entro il 10 Settembre 2026
        </div>
    </div>
    
    <!-- Bottom Addresses -->
    <table class="footer-addresses">
        <tr>
            <td class="address-left">
                <div>Gravina in Puglia</div>
                <div>Via Guardialto, 93</div>
            </td>
            <td class="address-right">
                <div>Gravina in Puglia</div>
                <div>Via Trieste, 68/B</div>
            </td>
        </tr>
    </table>
</body>
</html>
