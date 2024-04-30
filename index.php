<?php
/**
 * @package     Home Splash
 * @version     1.0
 * @author      Jules Potvin (help@mwlists.com)
 * @copyright   (C) 2001 - 2022 ROCKMYM3U.COM All rights reserved.
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0-standalone.html
 * @link        https://rockmym3u.com
 *
 * Home splash page to organize your internal links
 *
 */

// To change these values, create a file called config.php and copy/paste them there.
$server_name = "My Sever name";
$server_desc = "Media Server";
$color_bg = "#222";
$color_name = "#fff";
$color_text = "#ccc";
$custom_css = "";

//
// Links for your internal apps
//
$json = '{
    "Links": [
        {
            "name": "Emby",
            "link": "http://192.168.1.5:8096",
            "icon": "Emby.png"
        },
        {
            "name": "Prowlarr",
            "link": "http://192.168.1.6:9696",
            "icon": "data:image/png;base64,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"
        },
        {
            "name": "Radarr",
            "link": "http://192.168.1.7:7878",
            "icon": "Radarr.png"
        },
        {
            "name": "Sonarr",
            "link": "http://192.168.1.8:8989",
            "icon": "Sonarr.png"
        },
        {
            "name": "Jellyseerr",
            "link": "http://192.168.1.9:5055/",
            "icon": "jelly.svg"
        }
    ]
}';


if (is_file("config.php")) {
        include "config.php";
}

// Detect Windows systems
$windows = defined("PHP_WINDOWS_VERSION_MAJOR");

// Detect Mac systems
$mac = PHP_OS == "Darwin";

// Get system status
if ($windows) {

        // Uptime parsing was a mess...
        $uptime = "Error";

        // Assuming C: as the system drive
        $df = shell_exec("fsutil volume diskfree c:");
        $disk_stats = explode(" ", trim(preg_replace("/\s+/", " ", preg_replace("/[^0-9 ]+/", "", $df))));
        $disk = round($disk_stats[0] / $disk_stats[1] * 100);

        $disk_total = 0;
        $disk_used = 0;

        // Memory checking is slow on Windows, will only set over AJAX to allow page to load faster
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

        // Check disk stats
        if ($mac) {
                $disk_result = shell_exec("df -P | grep /System/Volumes/Data$");
        } else {
                $disk_result = shell_exec("df -P | grep /$");
        }
        $disk_result = explode(" ", preg_replace("/\s+/", " ", $disk_result));

        $disk_total = intval($disk_result[1]);
        $disk_used = intval($disk_result[2]);
        $disk = intval(rtrim($disk_result[4], "%"));

        // Get current RAM and Swap stats
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
                "disk" => $disk,
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
        ]));
}

$ringBase = 339.292;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $server_name; ?></title>
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
</style>
</head>
<body>
        <main class="main">
                <h1><?php echo $server_name; ?></h1>
                <p><?php echo $server_desc; ?></p>
        </main>

<?php

// Decode JSON
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
                                <circle class="ring-value" cx="60" cy="60" r="54" stroke-dashoffset="<?php echo $ringBase * (1 - ($disk/100)); ?>" />
                        </svg>
                        <div class="ring-label" x="60" y="72"><?php echo $disk ?></div>
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
                        <b>Disk:</b> <span id="dt-disk-used"><?php echo round($disk_used / 1048576, 2); ?></span> GB / <?php echo round($disk_total / 1048576, 2); ?> GB<br>
                        <b>Memory:</b> <span id="dt-mem-used"><?php echo $mem_used; ?></span> MB / <?php echo $mem_total; ?> MB<br>
                        <?php if ($swap_total !== null) { ?>
                                <b>Swap:</b> <span id="dt-swap-used"><?php echo $swap_used ?></span> MB / <?php echo $swap_total ?> MB<br>
                        <?php } else { ?>
                                <b>Swap:</b> N/A<br>
                        <?php }?>
                        <b>CPU Threads:</b> <span id="dt-num-cpus"></span>
                </div>
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
                        document.getElementById('dt-disk-used').textContent = Math.round(data.disk_used / 10485.76) / 100;
                        document.getElementById('dt-mem-used').textContent = data.memory_used;
                        if (data.arch) {
                                document.getElementById('dt-num-cpus').textContent = data.num_cpus + ' (' + data.arch + ')';
                        } else {
                                document.getElementById('dt-num-cpus').textContent = data.num_cpus;
                        }
                        if (data.cpu_name) {
                                document.getElementById('dt-num-cpus').setAttribute('title', data.cpu_name);
                        }
                        if (data.swap_total && document.getElementById('dt-swap-used')) {
                                document.getElementById('dt-swap-used').textContent = data.swap_used;
                        }

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
        </script>
</body>
</html>
