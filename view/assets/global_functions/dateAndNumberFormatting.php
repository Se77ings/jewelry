<?php
// Função que converte uma data no formato YYYY-MM-DD para DD-MM-YYYY

function DatetimeYYYYMMDDtoDDMMYYYY($datetime, $separator = "/") {
    if ($datetime == null) {
        return "";
    }

    $dateParts = explode(" ", $datetime);
    $datePart = YYYYMMDDtoDDMMYYYY($dateParts[0], $separator);

    if (count($dateParts) > 1) {
        return $datePart;
    } else {
        return $datePart;
    }
}

function YYYYMMDDtoDDMMYYYY($date, $separator = "-") {
    if ($date == null) {
        return "";
    }
    return implode($separator, array_reverse(explode("-", $date)));
}

// Função que converte uma data no formato DD-MM-YYYY para YYYY-MM-DD
function DDMMYYYYtoYYYYMMDD($date, $separator = "-") {
    if ($date == null) {
        return "";
    }
    return implode($separator, array_reverse(explode("-", $date)));
}

// Função que converte uma data em objeto de data (Date) para o formato YYYY-MM-DD
function DateToYYYYMMDD($date) {
    $year = date("Y", $date);
    $day = date("d", $date);
    $month = date("m", $date);

    return "$year-$month-$day";
}

// Função que converte uma data em objeto de data (Date) para o formato DD-MM-YYYY
function DateToDDMMYYY($date) {
    $year = date("Y", $date);
    $day = date("d", $date);
    $month = date("m", $date);

    return "$day/$month/$year";
}

// Função que formata números trocando ponto por vírgula e adicionando duas casas decimais
function convertePonto($valor) {

    if (!is_string($valor)) {
        $valor = strval($valor);
    }

    if (strpos($valor, '.') === false && strpos($valor, ',') === false) {
        return number_format(floatval($valor), 2, ',', '.');
    }

    // Verifica se o número tem um dígito após o ponto decimal
    if (strpos($valor, '.') !== false) {
        $partes = explode('.', $valor);
        if (strlen($partes[1]) === 1) {
            // Adiciona um zero para representar os centavos
            $partes[1] .= '0';
            return str_replace('.', ',', implode(',', $partes));
        } elseif (strlen($partes[1]) === 2) {
            return str_replace('.', ',', $valor);
        }
    }

    // Verifica se o número tem vírgula em vez de ponto e corrige
    if (strpos($valor, ',') !== false) {
        return str_replace(',', '.', $valor);
    }

    // Se nenhum ajuste for necessário, retorna o valor original
    return $valor;
}

?>