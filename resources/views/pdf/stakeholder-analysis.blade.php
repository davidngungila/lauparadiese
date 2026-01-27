<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title>Stakeholder Type Analysis - {{ $analytics['stakeholder_label'] }}</title>

    <style>

        @page {

            margin: 15mm 15mm 20mm 15mm;

            size: A4 landscape;

        }

        body {

            font-family: 'DejaVu Sans', sans-serif;

            font-size: 9pt;

            color: #333;

            line-height: 1.4;

        }

        .header {

            margin-bottom: 25px;

            border-bottom: 3px solid #2d7a5f;

            padding-bottom: 15px;

        }

        .header-top {

            display: table;

            width: 100%;

            margin-bottom: 20px;

        }

        .header-left {

            display: table-cell;

            width: 60%;

            vertical-align: top;

        }

        .header-right {

            display: table-cell;

            width: 40%;

            vertical-align: top;

            text-align: right;

        }

        .organization-info {

            margin-bottom: 15px;

        }

        .organization-logo {

            font-size: 24px;

            font-weight: bold;

            color: #2d7a5f;

            margin-bottom: 8px;

        }

        .organization-name {

            font-size: 18pt;

            font-weight: bold;

            color: #2d7a5f;

            margin-bottom: 8px;

        }

        .organization-details {

            font-size: 8pt;

            color: #666;

            line-height: 1.6;

        }

        .organization-details p {

            margin: 2px 0;

        }

        .document-info {

            text-align: right;

        }

        .document-type {

            font-size: 14pt;

            font-weight: bold;

            color: #2d7a5f;

            margin-bottom: 8px;

        }

        .document-date {

            font-size: 9pt;

            color: #666;

            margin-bottom: 5px;

        }

        .org-section {

            background-color: #f9f9fa;

            padding: 15px;

            border-radius: 5px;

            margin: 20px 0;

            border-left: 4px solid #2d7a5f;

        }

        .org-section-title {

            color: #2d7a5f;

            font-size: 12pt;

            font-weight: bold;

            margin-bottom: 15px;

            padding-bottom: 8px;

            border-bottom: 2px solid #2d7a5f;

        }

        .org-details-grid {

            display: table;

            width: 100%;

        }

        .org-details-col {

            display: table-cell;

            width: 33.33%;

            padding: 0 10px;

            vertical-align: top;

        }

        .org-col-title {

            font-weight: bold;

            color: #2d7a5f;

            margin-bottom: 8px;

            font-size: 9pt;

        }

        .org-col-content {

            font-size: 8pt;

            line-height: 1.8;

        }

        .org-col-content p {

            margin: 3px 0;

        }

        .banner-image {

            width: 100%;

            max-width: 100%;

            height: auto;

            margin-bottom: 15px;

        }

        .report-header {

            text-align: center;

            margin-top: 15px;

        }

        .report-header h1 {

            color: #2d7a5f;

            margin: 10px 0 5px 0;

            font-size: 20pt;

            font-weight: bold;

        }

        .report-header .subtitle {

            color: #2d7a47;

            font-size: 16pt;

            margin-bottom: 10px;

            font-weight: 600;

        }

        .report-header .report-info {

            color: #666;

            font-size: 9pt;

            margin-top: 10px;

        }

        .summary-section {

            margin: 20px 0;

            background: #f8f9fa;

            padding: 15px;

            border-left: 4px solid #2d7a5f;

        }

        .summary-grid {

            display: table;

            width: 100%;

            margin: 15px 0;

        }

        .summary-box {

            display: table-cell;

            width: 25%;

            padding: 10px;

            text-align: center;

            border: 1px solid #ddd;

            background-color: white;

            vertical-align: top;

        }

        .summary-box h3 {

            margin: 0;

            font-size: 18pt;

            color: #2d7a5f;

            font-weight: bold;

        }

        .summary-box p {

            margin: 5px 0 0 0;

            font-size: 9pt;

            color: #666;

        }

        .score-analysis {

            margin: 20px 0;

            background: #f0f8f4;

            padding: 15px;

            border: 1px solid #2d7a5f;

        }

        .score-analysis h3 {

            color: #2d7a5f;

            font-size: 12pt;

            margin-bottom: 10px;

        }

        .response-distribution {

            display: table;

            width: 100%;

            margin: 15px 0;

        }

        .distribution-item {

            display: table-cell;

            width: 20%;

            padding: 10px;

            text-align: center;

            border: 1px solid #ddd;

            background-color: white;

        }

        .distribution-item .count {

            font-size: 16pt;

            font-weight: bold;

            color: #2d7a5f;

        }

        .distribution-item .label {

            font-size: 8pt;

            color: #666;

            margin-top: 5px;

        }

        table {

            width: 100%;

            border-collapse: collapse;

            margin: 15px 0;

            font-size: 8pt;

            page-break-inside: auto;

        }

        th {

            background-color: #2d7a5f;

            color: white;

            padding: 8px 6px;

            text-align: left;

            font-weight: bold;

            font-size: 8pt;

        }

        td {

            padding: 6px;

            border-bottom: 1px solid #ddd;

            font-size: 8pt;

        }

        tr:nth-child(even) {

            background-color: #f8f9fa;

        }

        tr {

            page-break-inside: avoid;

            page-break-after: auto;

        }

        .section-title {

            color: #2d7a5f;

            font-size: 14pt;

            font-weight: bold;

            margin: 25px 0 15px 0;

            padding-bottom: 5px;

            border-bottom: 2px solid #2d7a5f;

        }

        .info-box {

            background: #f8f9fa;

            border-left: 4px solid #2d7a5f;

            padding: 12px;

            margin: 15px 0;

            font-size: 9pt;

        }

        .badge {

            padding: 3px 8px;

            border-radius: 4px;

            font-size: 8pt;

            font-weight: bold;

            display: inline-block;

        }

        .badge-primary {

            background-color: #2d7a5f;

            color: white;

        }

        .badge-success {

            background-color: #28a745;

            color: white;

        }

        .badge-warning {

            background-color: #ffc107;

            color: #333;

        }

        .badge-danger {

            background-color: #dc3545;

            color: white;

        }

        .badge-info {

            background-color: #17a2b8;

            color: white;

        }

        .question-item {

            margin: 15px 0;

            padding: 12px;

            background: #f8f9fa;

            border-left: 4px solid #2d7a5f;

            border-radius: 4px;

        }

        .question-header {

            font-weight: bold;

            color: #2d7a5f;

            font-size: 10pt;

            margin-bottom: 8px;

        }

        .question-text {

            font-size: 9pt;

            margin-bottom: 10px;

            color: #333;

        }

        .question-stats {

            display: table;

            width: 100%;

            margin-top: 10px;

        }

        .question-stat {

            display: table-cell;

            width: 33.33%;

            text-align: center;

            padding: 8px;

            border: 1px solid #ddd;

            background: white;

        }

        .question-stat-value {

            font-size: 14pt;

            font-weight: bold;

            color: #2d7a5f;

        }

        .question-stat-label {

            font-size: 8pt;

            color: #666;

            margin-top: 5px;

        }

        .footer {

            margin-top: 30px;

            padding-top: 15px;

            border-top: 2px solid #2d7a5f;

            text-align: center;

            font-size: 8pt;

            color: #666;

            page-break-inside: avoid;

        }

    </style>

</head>

<body>

    <!-- Organization Details Header -->
    @include('components.pdf-header', [
        'documentTitle' => 'Stakeholder Type Analysis Report - ' . $analytics['stakeholder_label'],
        'documentRef' => 'STAKE-' . now()->format('YmdHis'),
        'documentDate' => date('F d, Y'),
        'mainColor' => '#2d7a5f'
    ])
    
    <!-- Report Info -->
    <div style="text-align: center; margin: 15px 0; padding: 10px; background: #f0f8f4; border-radius: 5px;">
        <div style="font-size: 10pt; color: #666;">
            <strong>Total Responses:</strong> {{ $analytics['total_responses'] }} | 
            <strong>Report Period:</strong> {{ $responses->min('created_at')?->format('M d, Y') }} to {{ $responses->max('created_at')?->format('M d, Y') }}
        </div>
    </div>

    <!-- Executive Summary -->

    <div class="summary-section">

        <h2 style="color: #2d7a5f; font-size: 14pt; margin: 0 0 15px 0;">Executive Summary</h2>

        <div class="summary-grid">

            <div class="summary-box" style="width: 100%;">

                <h3>{{ number_format($analytics['total_responses']) }}</h3>

                <p><strong>Total Responses</strong></p>

                <p style="font-size: 8pt; margin-top: 5px;">All responses</p>

            </div>

        </div>

    </div>

    @php

        $total = $analytics['total_scores'];

        $distributions = [

            'Strongly Disagree' => ['count' => $analytics['overall_distribution']['Strongly Disagree'], 'color' => '#dc3545'],

            'Disagree' => ['count' => $analytics['overall_distribution']['Disagree'], 'color' => '#fd7e14'],

            'Neutral' => ['count' => $analytics['overall_distribution']['Neutral'], 'color' => '#ffc107'],

            'Agree' => ['count' => $analytics['overall_distribution']['Agree'], 'color' => '#28a745'],

            'Strongly Agree' => ['count' => $analytics['overall_distribution']['Strongly Agree'], 'color' => '#2d7a5f'],

        ];

    @endphp

    <!-- Score Analysis -->

    <div class="score-analysis">

        <h3>Response Distribution</h3>

        <div class="response-distribution">

            @foreach($distributions as $label => $data)

            <div class="distribution-item">

                <div class="count">{{ $data['count'] }}</div>

                <div class="label">{{ $label }}</div>

                <div style="font-size: 7pt; color: #999; margin-top: 3px;">

                    @if($total > 0)

                        {{ number_format(($data['count'] / $total) * 100, 1) }}%

                    @else

                        0%

                    @endif

                </div>

            </div>

            @endforeach

        </div>

    </div>

    <!-- Question Performance -->

    @if(!empty($analytics['question_stats']))

    <div style="page-break-before: always;">

        <h3 class="section-title">Question Performance Analysis</h3>

        <div class="info-box">

            <strong>Note:</strong> This section shows detailed performance metrics for each question in this stakeholder type.

        </div>

        

        <table>

            <thead>

                <tr>

                    <th style="width: 10%;">Question #</th>

                    <th style="width: 60%;">Question Text</th>

                    <th style="width: 15%;">Total Responses</th>

                    <th style="width: 15%;">Distribution</th>

                </tr>

            </thead>

            <tbody>

                @foreach($analytics['question_stats'] as $qNum => $qStat)

                <tr>

                    <td><strong>Q{{ $qNum }}</strong></td>

                    <td style="font-size: 8pt;">{{ $qStat['question_text'] }}</td>

                    <td><strong>{{ $qStat['total_responses'] }}</strong></td>

                    <td style="font-size: 7pt;">

                        Strongly Disagree ({{ $qStat['distribution']['Strongly Disagree'] ?? 0 }}), 

                        Disagree ({{ $qStat['distribution']['Disagree'] ?? 0 }}), 

                        Neutral ({{ $qStat['distribution']['Neutral'] ?? 0 }}), 

                        Agree ({{ $qStat['distribution']['Agree'] ?? 0 }}), 

                        Strongly Agree ({{ $qStat['distribution']['Strongly Agree'] ?? 0 }})

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    @endif

    <!-- Generated Disclaimer -->
    @include('components.pdf-disclaimer')
    
    <!-- Footer Script -->
    @include('components.pdf-footer')

</body>

</html>

