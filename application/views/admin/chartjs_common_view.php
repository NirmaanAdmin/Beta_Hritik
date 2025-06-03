<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo _l('charts_overview'); ?></title>
    <script
        src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>

    <!-- Chart.js v4 (must match treemap v3) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-treemap@3"></script>

    <style>
        .chart-container {
            position: relative;
            /* height: 70vh; */
            min-height: 200px;
            width: 100%;
        }

        .chart-wrapper {
            margin-bottom: 30px;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        .chart-title {
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 50vh;
            }
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <div class="content">
            <div class="row">
                <?php if (empty($charts) || !is_array($charts)): ?>
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <?php echo _l('no_chart_data_provided'); ?>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($charts as $idx => $chart): ?>
                        <?php
                        $col   = $col_classes[$idx] ?? 'col-md-12';
                        $title = $chart['options']['plugins']['title']['text'] ?? '';
                        ?>
                        <div class="<?php echo $col; ?>">
                            <div class="chart-wrapper">
                                <?php if (!empty($title)): ?>

                                <?php endif; ?>
                                <div class="chart-container">
                                    <canvas id="chart-<?php echo $idx; ?>"></canvas>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php /* init_tail(); */?>
    <script>
        // ─── Helper to collect all leaf nodes (where .v is a number) ─────────────────
        function collectLeaves(node, leavesArray) {
            if (typeof node.v === 'number') {
                leavesArray.push(node);
            }
            if (Array.isArray(node.c)) {
                node.c.forEach(child => collectLeaves(child, leavesArray));
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const chartConfigs = <?php echo json_encode($charts ?? [], JSON_UNESCAPED_SLASHES); ?>;

            if (typeof Chart === 'undefined') {
                console.error('Chart.js not loaded');
                return;
            }

            // Optional: set a global font
            Chart.defaults.font.family = Chart.defaults.font.family ||
                "'Segoe UI','Helvetica Neue',Arial,sans-serif";
            Chart.defaults.font.size = Chart.defaults.font.size || 12;

            chartConfigs.forEach((cfg, index) => {
                const canvasEl = document.getElementById(`chart-${index}`);
                if (!canvasEl) return;
                const ctx = canvasEl.getContext('2d');

                // ─── BAR CHART tweaks (unchanged) ─────────────────────────────────────────
                // if (cfg.type === 'bar') {
                //     cfg.options.plugins = cfg.options.plugins || {};
                //     cfg.options.scales = cfg.options.scales || {};

                //     cfg.options.plugins.tooltip = cfg.options.plugins.tooltip || {};
                //     cfg.options.plugins.tooltip.callbacks = {
                //         label: function(context) {
                //             return context.parsed.x.toLocaleString();
                //         }
                //     };

                //     cfg.options.scales.x = cfg.options.scales.x || {};
                //     cfg.options.scales.x.ticks = {
                //         callback: function(value) {
                //             return value.toLocaleString();
                //         }
                //     };
                // }

                // ─── PIE CHART tweaks (unchanged) ─────────────────────────────────────────
                if (cfg.type === 'pie') {
                    cfg.options.plugins = cfg.options.plugins || {};
                    cfg.options.plugins.tooltip = cfg.options.plugins.tooltip || {};
                    cfg.options.plugins.tooltip.callbacks = {
                        label: function(context) {
                            const label = context.label || "";
                            const value = context.raw || 0;
                            const data = context.dataset.data || [];
                            const total = data.reduce((a, b) => a + b, 0);
                            const percent = total ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${percent}%)`;
                        }
                    };
                }

                // ─── TREEMAP: assign a unique HSL color to each leaf via `dataset.color` ──
                if (cfg.type === 'treemap') {
                    cfg.data.datasets.forEach(dataset => {
                        // Step A: collect all leaf nodes into an array
                        const leaves = [];
                        if (Array.isArray(dataset.tree)) {
                            dataset.tree.forEach(root => collectLeaves(root, leaves));
                        } else if (typeof dataset.tree === 'object') {
                            collectLeaves(dataset.tree, leaves);
                        }

                        // Step B: give each leaf its own HSL color
                        leaves.forEach((leafNode, i) => {
                            const hue = Math.round((i / leaves.length) * 360);
                            // 60% saturation, 50% lightness—tweak if you want brighter/darker
                            leafNode._color = `hsl(${hue}, 60%, 50%)`;
                        });

                        // Step C: assign that function to `dataset.color`
                        dataset.color = function(ctx) {
                            // Only color the actual data rectangles
                            if (ctx.type !== 'data') {
                                return 'transparent';
                            }
                            // ctx.raw is the leaf node object, so return its _color
                            return ctx.raw._color;
                        };
                    });
                }

                // ─── Finally, build the chart instance ────────────────────────────────────
                new Chart(ctx, {
                    type: cfg.type,
                    data: cfg.data,
                    options: cfg.options
                });
            });
        });
    </script>

</body>

</html>