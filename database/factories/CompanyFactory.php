<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * O nome do modelo associado a esta factory.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define o estado padrão do modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lista de estados brasileiros
        $states = [
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 
            'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 
            'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
        ];
        
        // Gera um CNPJ formatado fictício
        $cnpj = sprintf(
            '%02d.%03d.%03d/%04d-%02d',
            $this->faker->numberBetween(10, 99),
            $this->faker->numberBetween(100, 999),
            $this->faker->numberBetween(100, 999),
            $this->faker->numberBetween(1000, 9999),
            $this->faker->numberBetween(10, 99)
        );
        
        return [
            'name' => $this->faker->company(),
            'document' => $cnpj,
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->randomElement($states),
            'country' => 'Brasil',
            'zip_code' => $this->faker->postcode(),
            'phone' => $this->faker->phoneNumber(),
            'logo' => null, // Ou gerar um logo aleatório se desejar
        ];
    }
    
    /**
     * Estado para indicar que a empresa deve ter um logo.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withLogo(): Factory
    {
        return $this->state(function (array $attributes) {
            // Aqui você poderia gerar um logo ou usar um placeholder
            return [
                'logo' => 'logos/placeholder.png',
            ];
        });
    }
}
