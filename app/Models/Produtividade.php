<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produtividade extends Model
{
    use HasFactory;

    protected $table = 'produtividades';

    protected $fillable = [
        'linha_produto',
        'planta',
        'data_producao',
        'quantidade_produzida',
        'quantidade_defeitos',
    ];

    protected $casts = [
        'data_producao' => 'date',
    ];

    /**
     * Calcula a eficiência da linha (produtos bons / total produzido * 100)
     */
    public function getEficienciaAttribute(): float
    {
        if ($this->quantidade_produzida === 0) {
            return 0.0;
        }
        $bons = $this->quantidade_produzida - $this->quantidade_defeitos;
        return round(($bons / $this->quantidade_produzida) * 100, 2);
    }

    /**
     * Linhas de produto disponíveis
     */
    public static function linhasDisponiveis(): array
    {
        return [
            'Geladeira',
            'Máquina de Lavar',
            'TV',
            'Ar-Condicionado',
        ];
    }
}
