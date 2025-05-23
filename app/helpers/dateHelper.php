<?php
function formatDate($dateString) {
    if (empty($dateString)) return '';
    
    $date = new DateTime($dateString);
    $today = new DateTime();
    $yesterday = new DateTime('yesterday');
    
    if ($date->format('Y-m-d') === $today->format('Y-m-d')) {
        return 'Hoy';
    } elseif ($date->format('Y-m-d') === $yesterday->format('Y-m-d')) {
        return 'Ayer';
    } else {
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 
                 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        return $date->format('j') . ' ' . $meses[$date->format('n') - 1];
    }
}

function getDateStatus($date) {
    $taskDate = new DateTime($date);
    $today = new DateTime(date('Y-m-d'));
    
    if ($taskDate < $today) return 'past';
    if ($taskDate == $today) return 'today';
    return 'future';
}