<?php /* mode:php code:utf-8 */
/*
 * ロト6 当選番号シミュレーター
 */


function get_loto6_hit_number($start, $end, $c=null)
{
}

function check_loto6_number($hit_number, $lot_number)
{
    $hit_count = 0;
    foreach ($hit_number['NUM'] as $num) {
        if (in_array($num, $lot_number)) $hit_count++;
    }
    $rank = 0;
    switch ($hit_count) {
    case 3:
        $rank = 5;
        break;
    case 4:
        $rank = 4;
        break;
    case 5:
        $rank = 3;
        if (in_array($hit_number['BORNUS'], $lot_number)) $rank = 2;
        break;
    case 6:
        $rank = 1;
        break;
    }

    return array('ROUND' => $hit_number['ROUND'], 'RANK' => $rank, 'HIT' => $hit_number['NUM'], 'DRAW' => $lot_number);
}

function loto6simurator($start, $end, $drow_lot_func, $drow_span=2)
{
    $hits = get_loto6_hit_number($start, $end);
    if (!$hits) {
        error_log('Error Get Hit numbrts');
        return false;
    }
    $results = array();
    $span = 0;
    $current = $start;
    while ($current <= $end) {
        if (!$span) {
            $lot_number = call_user_func($drow_lot_func);
            if (!is_array($lot_number)) {
                error_log('Error Drow Lot number.');
                return false;
            }
            $span = $drow_span;
        }
        $span--;
        
        // 当選番号のチェック
        $result = check_loto6_number($hits[$current], $lot_number);
        if (!is_array($result)) {
            error_log('Error Check Lot number.');
            return false;
        }
        $results[] = $result;
    }
    return $results;
}