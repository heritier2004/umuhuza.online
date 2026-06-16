<?php

function locationLabel($province, $district, $sector, $cell) {
    return trim(implode(' / ', array_filter([$province, $district, $sector, $cell])));
}
