<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo _l('charts_overview'); ?></title>

    <!-- 1. Include Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Each .panel_s will hold one chart */
        .panel_s {
            margin-bottom: 20px;
        }

        .panel_s .panel-body {
            position: relative;
            /* height: 400px; */
            /* Reserve 400px height for the canvas */
        }

        .panel_s canvas {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <div class="content">
            <div class="row">
                <?php if (empty($charts) || !is_array($charts)): ?>
                    <div class="alert alert-warning">
                        <?php echo _l('no_chart_data_provided'); ?>
                    </div>
                <?php else: ?>
                    <!-- Loop over each chart definition and render it inside its own panel_s -->
                    <?php foreach ($charts as $idx => $chart): ?>
                        <?php
                        // If col_classes array exists and has an entry at this index, use it.
                        // Otherwise default to 'col-md-12'.
                        if (isset($col_classes) && is_array($col_classes) && isset($col_classes[$idx]) && !empty($col_classes[$idx])) {
                            $col = $col_classes[$idx];
                        } else {
                            $col = 'col-md-12';
                        }
                        ?>
                        <div class="<?php echo $col; ?>">
                            <div class="panel_s">
                                <div class="panel-body">
                                    <!-- Optional: If a title is defined under options.plugins.title.text, show it above the canvas -->
                                    <?php
                                    $header_text = '';
                                    if (
                                        isset($chart['options'])
                                        && isset($chart['options']['plugins'])
                                        && isset($chart['options']['plugins']['title'])
                                        && isset($chart['options']['plugins']['title']['text'])
                                    ) {
                                        $header_text = $chart['options']['plugins']['title']['text'];
                                    }
                                    ?>
                                    <?php if ($header_text !== ''): ?>
                                        <h4><?php echo html_escape($header_text); ?></h4>
                                    <?php endif; ?>

                                    <canvas id="chart-<?php echo $idx; ?>"></canvas>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 2. Instantiate Chart.js for every <canvas> -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bring the PHP $charts array into JS
            const chartConfigs = <?php echo json_encode($charts ?? []); ?>;

            // For each chart definition, create a new Chart instance
            chartConfigs.forEach((cfg, index) => {
                const ctx = document.getElementById(`chart-${index}`).getContext('2d');
                const config = {
                    type: cfg.type || 'bar',
                    data: {
                        labels: cfg.labels || [],
                        datasets: cfg.datasets || []
                    },
                    options: cfg.options || {}
                };
                new Chart(ctx, config);
            });
        });
    </script>
    <?php init_tail(); ?>
</body>

</html>