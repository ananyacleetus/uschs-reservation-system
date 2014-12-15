<?php
    function makeModsReadable() {
        $modsReadable = "";
        foreach($mods as &$mod) {
            $modsReadable .= $mods;
        }
        return $modsReadable;
    }
?>

<head>
    <style type="text/css">
        p, h1, h3 {
            text-align: center;
        }
    </style>
</head>

<h1 class="center">{{ teacher_name }}:</h1>
<br><br>
<h3 class="center">Your cart reservation for cart {{ cart_name }} on {{ date }} for mods <?php echo makeModsReadable(); ?> has been reserved successfully</h3>
<br><br>
<p>you will be notified the day of the reservation where to aquire the cart. If you have any other concerns please email <a href="mailto:alex@brufsky.org?Subject=Cart Concerns" target="_top">alex@brufsky.org</a></p>  