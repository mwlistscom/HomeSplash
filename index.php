<?php
/**
 * Server Dashboard Script
 *
 * This script generates a server dashboard with configurable settings and links.
 *
 * Copyright (C) 2024  Jules Potvin
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
if (is_file("config.php")) {
    include "config.php";
}

// Ensure icons directory exists
if (!is_dir('icons')) {
    mkdir('icons', 0755, true);
}

// Ensure config.php exists
if (!is_file('config.php')) {
    $defaultConfig = <<<EOT
<?php
\$server_name = "My Server";
\$server_desc = "A brief description of my server.";
\$color_bg = "#222222";
\$color_name = "#ffffff";
\$color_text = "#cccccc";
\$showdisk = "/";
\$custom_css = "";
EOT;
    file_put_contents('config.php', $defaultConfig);
    include 'config.php';
}

// Ensure links.json exists
if (!is_file('links.json')) {
    $defaultLinks = [
        'Links' => [
            [
                'name' => 'Google',
                'link' => 'https://www.google.com',
                'icon' => 'https://www.google.com/favicon.ico'
            ],
            [
                'name' => 'GitHub',
                'link' => 'https://www.github.com',
                'icon' => 'https://github.com/favicon.ico'
            ]
        ]
    ];
    file_put_contents('links.json', json_encode($defaultLinks, JSON_PRETTY_PRINT));
}

// Load JSON from the links.json file
$json = file_get_contents('links.json');
$data = json_decode($json, true);

$windows = defined("PHP_WINDOWS_VERSION_MAJOR");
$mac = PHP_OS == "Darwin";

// Get system status
if ($windows) {
    // Get all disk drives
    $df = shell_exec("wmic logicaldisk get size,freespace,caption");
    preg_match_all('/\b([A-Z]):[^\d]*(\d+)[^\d]*(\d+)/', $df, $matches, PREG_SET_ORDER);

    $drives = [];
    foreach ($matches as $match) {
        $drives[] = [
            'drive' => $match[1] . ':',
            'total' => intval($match[3]),
            'used' => intval($match[3]) - intval($match[2]),
            'free' => intval($match[2]),
            'percent' => round((intval($match[3]) - intval($match[2])) / intval($match[3]) * 100)
        ];
    }

    $disk_total = array_sum(array_column($drives, 'total'));
    $disk_used = array_sum(array_column($drives, 'used'));
    $disk = $disk_total ? round($disk_used / $disk_total * 100) : 0;
    $show_disk_usage = $disk;

    $memory = 0;
    $mem_total = 0;
    $mem_used = 0;
    $swap = null;
    $swap_total = null;
    $swap_used = null;
} else {
    if ($mac) {
        $initial_uptime = time() - rtrim(shell_exec("/usr/sbin/sysctl -n kern.boottime | awk '{print $4}'"), ",\n");
    } else {
        $initial_uptime = shell_exec("cut -d. -f1 /proc/uptime");
    }
    $days = floor($initial_uptime / 60 / 60 / 24);
    $hours = floor($initial_uptime / 60 / 60) % 24;
    $mins = floor($initial_uptime / 60) % 60;
    $secs = floor($initial_uptime) % 60;

    if ($days > 0) {
        $uptime = $days . "d " . $hours . "h";
    } elseif ($days == 0 && $hours > 0) {
        $uptime = $hours . "h " . $mins . "m";
    } elseif ($hours == 0 && $mins > 0) {
        $uptime = $mins . "m " . $secs . "s";
    } elseif ($mins < 0) {
        $uptime = $secs . "s";
    } else {
        $uptime = "Error retrieving uptime.";
    }

    // Check disk stats, including NFS mounts
    $disk_result = shell_exec("df -P -T");
    $disk_lines = explode("\n", trim($disk_result));
    $drives = [];

    // Skip the header line
    array_shift($disk_lines);

    foreach ($disk_lines as $line) {
        $parts = preg_split('/\s+/', $line);
        if (count($parts) >= 7 && in_array($parts[1], ['ext4', 'xfs', 'nfs', 'nfs4', 'cifs', 'vfat'])) {
            $drives[] = [
                'drive' => $parts[6],
                'total' => intval($parts[2]),
                'used' => intval($parts[3]),
                'free' => intval($parts[4]),
                'percent' => intval(rtrim($parts[5], '%'))
            ];
        }
    }

    $disk_total = array_sum(array_column($drives, 'total'));
    $disk_used = array_sum(array_column($drives, 'used'));
    $disk = $disk_total ? round($disk_used / $disk_total * 100) : 0;

    $show_disk_usage = 0;
    foreach ($drives as $drive) {
        if ($drive['drive'] === $showdisk) {
            $show_disk_usage = $drive['percent'];
            break;
        }
    }

    if ($mac) {
        // Calculate current RAM usage
        preg_match('/([0-9]+) bytes/', shell_exec("vm_stat | grep 'page size'"), $matches);
        $pageSize = !empty($matches[1]) ? intval($matches[1]) : 4096;
        $free = shell_exec("vm_stat | grep free | awk '{ print \$3 }' | sed 's/\.//'") * $pageSize / 1024 / 1024;
        $inactive = shell_exec("vm_stat | grep inactive | awk '{ print \$3 }' | sed 's/\.//'") * $pageSize / 1024 / 1024;
        $mem_total = round(intval(trim(shell_exec("/usr/sbin/sysctl -n hw.memsize"))) / 1024 / 1024);
        $mem_used = round($mem_total - $free - $inactive);
        $memory = round($mem_used / $mem_total * 100);

        // Calculate current swap usage
        $swapParts = explode('  ', shell_exec('/usr/sbin/sysctl -n vm.swapusage'));
        $swap_total = round(trim(explode('=', $swapParts[0])[1], ' M'));
        $swap_used = round(trim(explode('=', $swapParts[1])[1], ' M'));
        $swap = $swap_total ? round($swap_used / $swap_total * 100) : 0;
    } else {
        $meminfoStr = shell_exec('awk \'$3=="kB"{$2=$2/1024;$3=""} 1\' /proc/meminfo');
        $mem = [];
        foreach(explode("\n", trim($meminfoStr)) as $m) {
            $m = explode(": ", $m, 2);
            $mem[$m[0]] = trim($m[1]);
        }

        // Calculate current RAM usage
        $mem_total = round($mem['MemTotal']);
        $mem_used = $mem_total - round($mem['MemFree']) - round($mem['Cached']);
        $memory = round($mem_used / $mem_total * 100);

        // Calculate current swap usage
        $swap_total = round($mem['SwapTotal']);
        $swap_used = $swap_total - round($mem['SwapFree']);
        $swap = $swap_total ? round($swap_used / $swap_total * 100) : 0;
    }
}

if (!empty($_GET["json"])) {
    // Determine number of CPUs
    $cpu_name = null;
    $num_cpus = 1;
    if ($windows) {
        $process = @popen("wmic cpu get NumberOfCores", "rb");
        if (false !== $process) {
            fgets($process);
            $num_cpus = intval(fgets($process));
            pclose($process);
        }
    } elseif (is_file("/proc/cpuinfo")) {
        $cpuinfo = file_get_contents("/proc/cpuinfo");
        preg_match_all("/^processor/m", $cpuinfo, $matches);
        $num_cpus = count($matches[0]);
        if (preg_match("/^model name +: (.+)$/m", $cpuinfo, $matches)) {
            $cpu_name = $matches[1];
        }
    } elseif ($mac) {
        $cpu_name = trim(shell_exec("/usr/sbin/sysctl -n machdep.cpu.brand_string"));
        $num_cpus = intval(trim(shell_exec("/usr/sbin/sysctl -n hw.ncpu"))) ?: 1;
    } else {
        $process = @popen("sysctl -a", "rb");
        if (false !== $process) {
            $output = stream_get_contents($process);
            preg_match("/hw.ncpu: (\d+)/", $output, $matches);
            if ($matches) {
                $num_cpus = intval($matches[1][0]);
            }
            pclose($process);
        }
    }

    $arch = null;
    if ($windows) {
        // Get stats for Windows
        $cpu = intval(trim(preg_replace("/[^0-9]+/","",shell_exec("wmic cpu get loadpercentage"))));
        $memory_stats = explode(' ',trim(preg_replace("/\s+/"," ",preg_replace("/[^0-9 ]+/","",shell_exec("systeminfo | findstr Memory")))));
        $memory = round($memory_stats[4] / $memory_stats[0] * 100);
    } else {
        $arch = trim(shell_exec('uname -m'));
        // Get stats for linux using simplest/most accurate possible methods
        if (is_file("mpstat")) {
            $cpu = 100 - round(shell_exec("mpstat 1 2 | tail -n 1 | sed 's/.*\([0-9\.+]\{5\}\)$/\\1/'"));
        } elseif (function_exists("sys_getloadavg")) {
            $load = sys_getloadavg();
            $cpu = $load[0] * 100 / $num_cpus;
        } elseif (is_file("/proc/loadavg")) {
            $cpu = 0;
            $output = file_get_contents("/proc/loadavg");
            $cpu = substr($output,0,strpos($output," "));
        } elseif (is_file("uptime")) {
            $str = substr(strrchr(shell_exec("uptime"),":"),1);
            $avs = array_map("trim",explode(",",$str));
            $cpu = $avs[0] * 100 / $num_cpus;
        } else {
            $cpu = 0;
        }
    }

    header("Content-type: application/json");
    exit(json_encode([
        "uptime" => $uptime,
        "drives" => $drives,
        "disk_total" => $disk_total,
        "disk_used" => $disk_used,
        "cpu" => $cpu,
        "arch" => $arch,
        "cpu_name" => $cpu_name,
        "num_cpus" => $num_cpus,
        "memory" => $memory,
        "memory_total" => $mem_total,
        "memory_used" => $mem_used,
        "swap" => $swap,
        "swap_total" => $swap_total,
        "swap_used" => $swap_used,
        "show_disk_usage" => $show_disk_usage
    ]));
}

$ringBase = 339.292;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $server_name; ?></title>
<link rel="stylesheet" type="text/css" href="https://unpkg.com/tabulator-tables@5.4.4/dist/css/tabulator.min.css">
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style type="text/css">
body {
    height: 60vh;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    background: <?php echo $color_bg; ?>;
    overflow: hidden;
}
.main, .footer {
    padding-left: 15%;
    padding-right: 15%;
}

.main {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: start;
    padding-top: 6.5rem;
}
.main h1 {
    font-size: 4rem;
    font-weight: 300;
    margin: 0;
    color: <?php echo $color_name; ?>;
}
.main p {
    font-size: 2rem;
    font-weight: 300;
    margin: 0;
    color: <?php echo $color_text; ?>;
}

a, a:link, a:visited {
    color: <?php echo $color_name; ?>;
    text-decoration: none;
    cursor: pointer;
}
a:hover, a:focus, a:active {
    color: <?php echo $color_name; ?>;
    text-decoration: underline;
}

.footer {
    display: flex;
    align-items: center;
    padding-top: 4rem;
    padding-bottom: 2rem;
    line-height: 2.5rem;
    color: <?php echo $color_text; ?>;
}
.footer > div {
    margin-right: 1rem;
}
.footer-end {
    margin-left: auto;
    margin-right: 0;
}

.ring-container {
    position: relative;
    display: flex;
    align-items: center;
}
.ring {
    transform: rotate(-90deg);
    fill: none;
    stroke-width: 12;
    height: 2.5rem;
    width: 2.5rem;
    margin-left: 0.25rem;
}
.ring-background {
    stroke: rgba(127,127,127,0.15);
}
.ring-value {
    stroke: <?php echo $color_text; ?>;
    stroke-dasharray: <?php echo $ringBase; ?>;
}
.ring-label {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 40px;
    line-height: 40px;
    text-align: center;
    fill: <?php echo $color_text; ?>;
    font-size: 0.85rem;
}

.overlay {
    z-index: 1;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: black;
    opacity: 0.3;
}
.details {
    z-index: 2;
    position: absolute;
    box-sizing: border-box;
    padding: 1em 15%;
    bottom: 0;
    left: 0;
    width: 100%;
    min-height: 6rem;

    display: flex;
    justify-content: space-between;

    background-color: <?php echo $color_text; ?>;
    color: <?php echo $color_bg; ?>;

    transform: translateY(100%);
    transition: transform .2s cubic-bezier(.15,.75,.55,1);
}
.details.open {
    transform: translateY(0);
}
.details h2 {
    color: <?php echo $color_bg; ?>;
    font-weight: 100;
    font-size: 2em;
    margin: 0;
    line-height: 1.3;
}

/* Begin: Custom CSS */
<?php echo $custom_css; ?>
/* End: Custom CSS */

#links-editor, #config-editor {
    display: none;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ccc;
}
#add-link-form .form-group {
    margin-bottom: 1em;
}
#add-link-form label {
    display: inline-block;
    width: 120px;
    text-align: right;
    margin-right: 10px;
}
#add-link-form input, #add-link-form textarea {
    width: 250px;
}
.form-group {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}
.form-group label {
    flex: 0 0 150px;
    text-align: right;
    margin-right: 10px;
}
.form-group input, .form-group textarea {
    flex: 1;
}
#custom-css {
    height: 100px;
    resize: vertical;
}
</style>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>
</head>
<body>
    <main class="main">
        <h1><?php echo $server_name; ?></h1>
        <p><?php echo $server_desc; ?></p>
    </main>

<?php
// Load JSON from the links.json file
$json = file_get_contents('links.json');
$data = json_decode($json, true);

// Check if "Links" key exists
if (isset($data['Links'])) {
    echo '<div style="margin-left: 25%;">'; // Set left margin to 25%
    echo '<table style="width: 50%;">'; // Set table width to 50%
    echo '<tr>';
    $count = 0;
    // Loop through each link
    foreach ($data['Links'] as $link) {
        // Output link name, URL, and icon with target="_blank" to open in a new window
        echo '<td style="text-align: left;">';
        echo '<a href="' . $link['link'] . '" target="_blank">';
        echo '<img src="' . $link['icon'] . '" alt="' . $link['name'] . '" width="75" height="75"><br>'; // Display icon
        echo $link['name'] . '</a>';
        echo '</td>';
        $count++;
        // If 5 columns are reached, start a new row
        if ($count % 5 == 0) {
            echo '</tr><tr>';
            echo '<br><br><br><br>';
        }
    }
    echo '</tr>';
    echo '</table>';
    echo '</div>';
}
?>

    <footer class="footer">
        <?php if (!$windows && !empty($uptime)) { ?>
            <div>Uptime: <span id="uptime"><?php echo $uptime; ?></span></div>
        <?php } ?>
        <div class="ring-container" id="k-disk">
            Disk usage:
            <svg class="ring" viewBox="0 0 120 120">
                <circle class="ring-background" cx="60" cy="60" r="54" />
                <circle class="ring-value" cx="60" cy="60" r="54" stroke-dashoffset="<?php echo $ringBase * (1 - ($show_disk_usage/100)); ?>" />
            </svg>
            <div class="ring-label" x="60" y="72"><?php echo $show_disk_usage ?></div>
        </div>
        <div class="ring-container" id="k-memory">
            Memory:
            <svg class="ring" viewBox="0 0 120 120">
                <circle class="ring-background" cx="60" cy="60" r="54" />
                <circle class="ring-value" cx="60" cy="60" r="54" stroke-dashoffset="<?php echo $ringBase * (1 - ($memory / 100)); ?>" />
            </svg>
            <div class="ring-label" x="60" y="72"><?php echo $memory ?: null ?></div>
        </div>
        <?php if ($swap_total !== null) { ?>
            <div class="ring-container" id="k-swap">
                Swap:
                <svg class="ring" viewBox="0 0 120 120">
                    <circle class="ring-background" cx="60" cy="60" r="54" />
                    <circle class="ring-value" cx="60" cy="60" r="54" stroke-dashoffset="<?php echo $ringBase * (1 - ($swap / 100)); ?>" />
                </svg>
                <div class="ring-label" x="60" y="72"><?php echo $swap ?></div>
            </div>
        <?php } ?>
        <div class="ring-container" id="k-cpu">
            CPU:
            <svg class="ring" viewBox="0 0 120 120">
                <circle class="ring-background" cx="60" cy="60" r="54" />
                <circle class="ring-value" cx="60" cy="60" r="54" stroke-dashoffset="<?php echo $ringBase; ?>" />
            </svg>
            <div class="ring-label" x="60" y="72"></div>
        </div>
        <div class="footer-end">
            <a href="#" id="detail">Detail</a>
            <a href="#" id="edit-links" style="margin-left: 1rem;">Links</a>
            <a href="#" id="edit-config" style="margin-left: 1rem;">Config</a>
        </div>
    </footer>
    <div class="details" aria-hidden="true">
        <div>
            <h2><?php echo $windows ? $_SERVER["SERVER_NAME"] : shell_exec("hostname -f"); ?></h2>
            <?php
                if (!$windows) {
                    $version = null;
                    if (is_file("/etc/issue")) {
                        $version_arr = explode("\\", file_get_contents("/etc/issue"));
                        $version = $version_arr[0];
                    } else {
                        $version_cmd = shell_exec("lsb_release -d");
                        if ($version_cmd && strpos($version_cmd, "Description") === 0) {
                            $version = preg_replace("/^Description:\\s/", "", $version_cmd);
                        }
                    }
                    echo $version ? $version . "<br>" : "";
                }
            ?>
            <?php echo $_SERVER["SERVER_ADDR"]; ?>
        </div>
        <div>
            <?php foreach ($drives as $drive) { ?>
                <b><?php echo $drive['drive']; ?>:</b> <span><?php echo round($drive['used'] / 1048576, 2); ?></span> GB / <?php echo round($drive['total'] / 1048576, 2); ?> GB (<?php echo $drive['percent']; ?>%)<br>
            <?php } ?>
            <b>Memory:</b> <span id="dt-mem-used"><?php echo $mem_used; ?></span> MB / <?php echo $mem_total; ?> MB<br>
            <?php if ($swap_total !== null) { ?>
                <b>Swap:</b> <span id="dt-swap-used"><?php echo $swap_used ?></span> MB / <?php echo $swap_total ?> MB<br>
            <?php } else { ?>
                <b>Swap:</b> N/A<br>
            <?php }?>
            <b>CPU Threads:</b> <span id="dt-num-cpus"></span>
        </div>
    </div>
    <div id="links-editor" style="display:none;">
        <div id="links-table"></div>
        <button id="add-link">Add Link</button>
    </div>
    <div id="add-link-form" title="Add Link" style="display:none;">
        <form>
            <div class="form-group">
                <label for="link-name">Name:</label>
                <input type="text" id="link-name" name="link-name">
            </div>
            <div class="form-group">
                <label for="link-url">Link:</label>
                <input type="text" id="link-url" name="link-url">
            </div>
            <div class="form-group">
                <label for="link-icon">Icon:</label>
                <input type="text" id="link-icon" name="link-icon">
            </div>
            <div class="form-group">
                <label for="icon-file">Upload Icon:</label>
                <input type="file" id="icon-file" name="icon-file">
            </div>
        </form>
    </div>
    <div id="config-editor" style="display:none;">
        <form>
            <div class="form-group">
                <label for="server-name">Server Name:</label>
                <input type="text" id="server-name" name="server-name" value="<?php echo $server_name; ?>">
            </div>
            <div class="form-group">
                <label for="server-desc">Server Description:</label>
                <input type="text" id="server-desc" name="server-desc" value="<?php echo $server_desc; ?>">
            </div>
            <div class="form-group">
                <label for="color-bg">Background Color:</label>
                <input type="color" id="color-bg" name="color-bg" value="<?php echo $color_bg; ?>">
            </div>
            <div class="form-group">
                <label for="color-name">Name Color:</label>
                <input type="color" id="color-name" name="color-name" value="<?php echo $color_name; ?>">
            </div>
            <div class="form-group">
                <label for="color-text">Text Color:</label>
                <input type="color" id="color-text" name="color-text" value="<?php echo $color_text; ?>">
            </div>
            <div class="form-group">
                <label for="showdisk">Disk to Show:</label>
                <input type="text" id="showdisk" name="showdisk" value="<?php echo $showdisk; ?>">
            </div>
            <div class="form-group">
                <label for="custom-css">Custom CSS:</label>
                <textarea id="custom-css" name="custom-css"><?php echo $custom_css; ?></textarea>
            </div>
        </form>
    </div>
    <script>
    var ringBase = parseFloat('<?php echo $ringBase; ?>');

    function update() {
        var xhr = new XMLHttpRequest();
        xhr.addEventListener('load', function() {
            data = JSON.parse(xhr.responseText);

            // Update footer
            if (document.getElementById('uptime')) {
                document.getElementById('uptime').textContent = data.uptime;
            }
            document.querySelector('#k-cpu .ring-value').setAttribute('stroke-dashoffset', ringBase * (1 - (data.cpu / 100)));
            document.querySelector('#k-cpu .ring-label').textContent = Math.round(data.cpu);
            document.querySelector('#k-memory .ring-value').setAttribute('stroke-dashoffset', ringBase * (1 - (data.memory / 100)));
            document.querySelector('#k-memory .ring-label').textContent = Math.round(data.memory);
            if (data.swap_total) {
                document.querySelector('#k-swap .ring-value').setAttribute('stroke-dashoffset', ringBase * (1 - (data.swap / 100)));
                document.querySelector('#k-swap .ring-label').textContent = Math.round(data.swap);
            }

            // Update details
            let detailsHTML = '';
            data.drives.forEach(drive => {
                detailsHTML += `<b>${drive.drive}:</b> <span>${Math.round(drive.used / 10485.76) / 100}</span> GB / ${Math.round(drive.total / 10485.76) / 100} GB (${drive.percent}%)<br>`;
            });
            detailsHTML += `<b>Memory:</b> <span id="dt-mem-used">${data.memory_used}</span> MB / ${data.memory_total} MB<br>`;
            if (data.swap_total) {
                detailsHTML += `<b>Swap:</b> <span id="dt-swap-used">${data.swap_used}</span> MB / ${data.swap_total} MB<br>`;
            } else {
                detailsHTML += `<b>Swap:</b> N/A<br>`;
            }
            detailsHTML += `<b>CPU Threads:</b> <span id="dt-num-cpus">${data.num_cpus} (${data.arch})</span>`;
            if (data.cpu_name) {
                detailsHTML += `<span title="${data.cpu_name}"></span>`;
            }

            document.querySelector('.details div:last-child').innerHTML = detailsHTML;

            window.setTimeout(update, 3000);
        });
        xhr.open('POST', '<?php echo basename(__FILE__); ?>?json=1');
        xhr.send();
    }

    // Start AJAX update loop
    update();

    // Bind events
    document.getElementById('detail').addEventListener('click', function(e) {
        e.preventDefault();

        let details = document.getElementsByClassName('details')[0];
        details.classList.add('open');

        let overlay = document.createElement('div');
        overlay.className = 'overlay';
        document.body.appendChild(overlay);
    });
    document.body.addEventListener('click', function(e) {
        if (e.target.className == 'overlay') {
            let details = document.getElementsByClassName('details')[0];
            details.classList.remove('open');
            e.target.remove();
        }
    });
document.getElementById('edit-links').addEventListener('click', function(e) {
    e.preventDefault();

    // Load data from links.json every time Links is clicked
    $.getJSON('links.json', function(data) {
        var table = new Tabulator("#links-table", {
            data: data.Links,
            layout: "fitColumns",
            height: "auto", // Adjust height to fit all rows
            columns: [
                { title: "Name", field: "name", editor: "input", cellEdited: saveTableData },
                { title: "Link", field: "link", editor: "input", cellEdited: saveTableData },
                { title: "Icon", field: "icon", editor: "input", cellEdited: saveTableData },
                { formatter: "buttonCross", width: 40, hozAlign: "center", cellClick: function(e, cell) {
                    cell.getRow().delete();
                    saveTableData(); // Save data after deleting row
                }}
            ]
        });

        $('#links-editor').dialog({
            modal: true,
            width: '80%',
            close: function () {
                table.destroy();
                $('#links-editor').hide();
                location.reload();
            }
        });

        // Add link functionality
        $('#add-link').off('click').on('click', function() {
    // Clear form fields
    $('#link-name').val('');
    $('#link-url').val('');
    $('#link-icon').val('');
    $('#icon-file').val('');

            $('#add-link-form').dialog({
                modal: true,
                width: 'auto',
                buttons: {
                    "Add": function() {
                        var name = $('#link-name').val();
                        var link = $('#link-url').val();
                        var icon = $('#link-icon').val();
                        var fileInput = $('#icon-file')[0];

                        if (fileInput.files.length > 0) {
                            var formData = new FormData();
                            formData.append('icon_file', fileInput.files[0]);

                            $.ajax({
                                url: 'upload_icon.php',
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    if (response.success) {
                                        icon = 'icons/' + response.filename;
                                    }
                                    addRowToTable(name, link, icon);
                                }
                            });
                        } else {
                            addRowToTable(name, link, icon);
                        }

                        $(this).dialog('close');
                    },
                    "Cancel": function() {
                        $(this).dialog('close');
                    }
                }
            });
        });
    });
});

function addRowToTable(name, link, icon) {
    var table = Tabulator.findTable('#links-table')[0];
    table.addRow({ name: name, link: link, icon: icon });
    saveTableData();
}

function saveTableData() {
    var table = Tabulator.findTable('#links-table')[0];
    var data = table.getData();
    $.ajax({
        url: 'save_links.php',
        type: 'POST',
        data: JSON.stringify({ Links: data }),
        contentType: 'application/json',
        success: function() {
            // Removed alert for "Links saved successfully!"
            // Location reload will happen when the dialog is closed
        }
    });
}

document.getElementById('edit-config').addEventListener('click', function(e) {
    e.preventDefault();

    $('#config-editor').dialog({
        modal: true,
        width: '50%',
        buttons: {
            "Save": function() {
                var configData = {
                    server_name: $('#server-name').val(),
                    server_desc: $('#server-desc').val(),
                    color_bg: $('#color-bg').val(),
                    color_name: $('#color-name').val(),
                    color_text: $('#color-text').val(),
                    showdisk: $('#showdisk').val(),
                    custom_css: $('#custom-css').val()
                };

                $.ajax({
                    url: 'save_config.php',
                    type: 'POST',
                    data: JSON.stringify(configData),
                    contentType: 'application/json',
                    success: function() {
                         window.location.href = window.location.href.split('?')[0]; // Force reload of the page to re-read the config.php
                    }
                });

                $(this).dialog('close');
            },
            "Cancel": function() {
                $(this).dialog('close');
            }
        }
    });
});