<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * O nome do modelo associado a esta factory.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define o estado padrão do modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lista de categorias comuns para comércios
        $categoryNames = [
            'Eletrônicos', 'Informática', 'Celulares', 'Áudio e Vídeo', 
            'Eletrodomésticos', 'Móveis', 'Decoração', 'Cama, Mesa e Banho',
            'Utensílios Domésticos', 'Ferramentas', 'Jardim', 'Material de Construção',
            'Papelaria', 'Livros', 'Brinquedos', 'Games', 'Esportes', 'Fitness',
            'Moda Masculina', 'Moda Feminina', 'Moda Infantil', 'Calçados',
            'Acessórios', 'Joias e Relógios', 'Beleza e Perfumaria', 'Saúde',
            'Alimentos', 'Bebidas', 'Produtos de Limpeza', 'Pet Shop',
            'Automotivo', 'Instrumentos Musicais'
        ];
        
        // Escolhe um nome aleatório da lista
        $name = $this->faker->unique()->randomElement($categoryNames);
        
        // Gera um slug a partir do nome
        $slug = Str::slug($name);
        
        return [
            'name' => $name,
            'description' => $this->faker->sentence(10),
            'image' => null, // Por padrão sem imagem
            'company_id' => function () {
                // Pega uma empresa aleatória ou cria uma se não existir
                return Company::inRandomOrder()->first()?->id ?? 
                       Company::factory()->create()->id;
            },
        ];
    }
    
    /**
     * Estado para indicar que a categoria deve ter uma imagem.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withImage(): Factory
    {
        return $this->state(function (array $attributes) {
            // Define um caminho de imagem para a categoria
            return [
                'image' => 'categories/category_' . Str::slug($attributes['name']) . '.jpg',
            ];
        });
    }
    
    /**
     * Estado para associar a categoria a uma empresa específica.
     *
     * @param \App\Models\Company $company
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forCompany(Company $company): Factory
    {
        return $this->state(function (array $attributes) use ($company) {
            return [
                'company_id' => $company->id,
            ];
        });
    }
}
