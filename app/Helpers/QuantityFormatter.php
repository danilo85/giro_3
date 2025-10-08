<?php

namespace App\Helpers;

class QuantityFormatter
{
    /**
     * Formatar quantidade para exibição mais limpa
     * Remove decimais desnecessários e converte frações comuns para símbolos Unicode
     */
    public static function format($quantity)
    {
        // Converte para float para garantir que é numérico
        $number = (float) $quantity;
        
        // Se é um número inteiro, retorna sem decimais
        if ($number == floor($number)) {
            return (string) (int) $number;
        }
        
        // Mapear frações decimais comuns para símbolos Unicode
        $fractions = [
            '0.125' => '⅛',
            '0.25' => '¼',
            '0.33' => '⅓',
            '0.333' => '⅓',
            '0.375' => '⅜',
            '0.5' => '½',
            '0.625' => '⅝',
            '0.66' => '⅔',
            '0.666' => '⅔',
            '0.667' => '⅔',
            '0.75' => '¾',
            '0.875' => '⅞',
        ];
        
        // Verificar se é uma fração simples
        $decimal = $number - floor($number);
        
        // Arredondar para 3 casas decimais para comparação
        $roundedDecimal = round($decimal, 3);
        $roundedDecimalStr = (string) $roundedDecimal;
        
        if (isset($fractions[$roundedDecimalStr])) {
            $integerPart = (int) floor($number);
            $fractionSymbol = $fractions[$roundedDecimalStr];
            
            if ($integerPart > 0) {
                return $integerPart . $fractionSymbol;
            } else {
                return $fractionSymbol;
            }
        }
        
        // Para outros decimais, verificar se são próximos de frações conhecidas
        foreach ($fractions as $decimalValueStr => $symbol) {
            $decimalValue = (float) $decimalValueStr;
            if (abs($roundedDecimal - $decimalValue) < 0.01) {
                $integerPart = (int) floor($number);
                
                if ($integerPart > 0) {
                    return $integerPart . $symbol;
                } else {
                    return $symbol;
                }
            }
        }
        
        // Se não é uma fração comum, formatar com até 2 casas decimais, removendo zeros desnecessários
        $formatted = number_format($number, 2, '.', '');
        
        // Remove zeros à direita após o ponto decimal
        $formatted = rtrim($formatted, '0');
        $formatted = rtrim($formatted, '.');
        
        return $formatted;
    }
    
    /**
     * Formatar quantidade com unidade e pluralização
     */
    public static function formatWithUnit($quantity, $unit)
    {
        $formattedQuantity = self::format($quantity);
        
        // Pluralização básica para unidades comuns
        $pluralUnits = [
            'unidade' => 'unidades',
            'dente' => 'dentes',
            'folha' => 'folhas',
            'ramo' => 'ramos',
            'fatia' => 'fatias',
            'pitada' => 'pitadas',
        ];
        
        // Verificar se a quantidade é maior que 1 para pluralizar
        $numericQuantity = (float) $quantity;
        
        if ($numericQuantity > 1 && isset($pluralUnits[$unit])) {
            $unit = $pluralUnits[$unit];
        }
        
        return $formattedQuantity . ' ' . $unit;
    }
}