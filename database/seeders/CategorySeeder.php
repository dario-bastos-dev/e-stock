<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Executa o seeder para popular as categorias.
     *
     * @return void
     */
    public function run(): void
    {
        // Limpa a pasta de imagens de categorias se existir
        if (Storage::exists('public/categories')) {
            Storage::deleteDirectory('public/categories');
        }
        
        // Cria o diretório para as imagens de categorias
        Storage::makeDirectory('public/categories');
        
        // 1. Categorias padrão para todas as empresas (dados essenciais)
        $this->createDefaultCategories();
        
        // 2. Categorias adicionais aleatórias (para ambiente de desenvolvimento/testes)
        if (app()->environment(['local', 'development', 'testing'])) {
            $this->createRandomCategories();
        }
        
        $this->command->info('Categorias criadas com sucesso!');
    }
    
    /**
     * Cria categorias padrão para cada empresa.
     *
     * @return void
     */
    private function createDefaultCategories(): void
    {
        // Lista de categorias padrão que toda empresa deve ter
        $defaultCategories = [
            [
                'name' => 'Geral',
                'description' => 'Produtos gerais e não categorizados',
            ],
            [
                'name' => 'Mais Vendidos',
                'description' => 'Produtos mais populares e vendidos',
            ],
            [
                'name' => 'Novidades',
                'description' => 'Produtos recém adicionados ao catálogo',
            ],
            [
                'name' => 'Promoções',
                'description' => 'Produtos com descontos e ofertas especiais',
            ],
        ];
        
        // Obtém todas as empresas
        $companies = Company::all();
        
        // Para cada empresa, cria as categorias padrão
        foreach ($companies as $company) {
            foreach ($defaultCategories as $categoryData) {
                // Verifica se a categoria já existe para esta empresa (evita duplicatas)
                Category::firstOrCreate(
                    [
                        'name' => $categoryData['name'],
                        'company_id' => $company->id,
                    ],
                    [
                        'description' => $categoryData['description'],
                        'image' => null,
                    ]
                );
            }
        }
    }
    
    /**
     * Cria categorias aleatórias para desenvolvimento e testes.
     *
     * @return void
     */
    private function createRandomCategories(): void
    {
        // Obtém todas as empresas
        $companies = Company::all();
        
        // Lista de categorias específicas por tipo de comércio
        $commerceTypes = [
            'Supermercado' => [
                'Mercearia', 'Hortifruti', 'Açougue', 'Padaria', 'Frios e Laticínios',
                'Bebidas', 'Limpeza', 'Higiene Pessoal', 'Pet Shop', 'Congelados'
            ],
            'Loja de Informática' => [
                'Computadores', 'Notebooks', 'Periféricos', 'Componentes', 'Impressoras',
                'Redes', 'Armazenamento', 'Software', 'Gamer', 'Cabos e Adaptadores'
            ],
            'Loja de Roupas' => [
                'Masculino', 'Feminino', 'Infantil', 'Calçados', 'Acessórios',
                'Esportivo', 'Moda Praia', 'Underwear', 'Plus Size', 'Inverno'
            ],
            'Farmácia' => [
                'Medicamentos', 'Dermocosméticos', 'Higiene Pessoal', 'Vitaminas',
                'Ortopédicos', 'Saúde Sexual', 'Bebê', 'Fitness', 'Primeiros Socorros'
            ],
        ];
        
        foreach ($companies as $company) {
            // Escolhe aleatoriamente um tipo de comércio para a empresa (para simular diferentes negócios)
            $commerceType = array_rand($commerceTypes);
            
            // Obtém as categorias desse tipo de comércio
            $categories = $commerceTypes[$commerceType];
            
            // Cria entre 5 e 8 categorias aleatórias para esta empresa
            $categoriesToCreate = rand(5, 8);
            
            // Embaralha o array para pegar categorias aleatórias
            shuffle($categories);
            
            // Pega apenas as primeiras X categorias embaralhadas
            $selectedCategories = array_slice($categories, 0, $categoriesToCreate);
            
            // Cria as categorias selecionadas para esta empresa
            foreach ($selectedCategories as $categoryName) {
                // Verifica se a categoria já existe para esta empresa
                if (!Category::where('name', $categoryName)
                             ->where('company_id', $company->id)
                             ->exists()) {
                    
                    Category::create([
                        'name' => $categoryName,
                        'description' => $this->generateDescription($categoryName, $commerceType),
                        'image' => 'categories/' . Str::slug($categoryName) . '.jpg',
                        'company_id' => $company->id,
                    ]);
                }
            }
            
            // Além disso, cria 2-3 categorias totalmente aleatórias usando a factory
            Category::factory()
                ->count(rand(2, 3))
                ->forCompany($company)
                ->create();
        }
    }
    
    /**
     * Gera uma descrição baseada no nome da categoria e tipo de comércio.
     *
     * @param string $categoryName
     * @param string $commerceType
     * @return string
     */
    private function generateDescription(string $categoryName, string $commerceType): string
    {
        $templates = [
            "Produtos de {$categoryName} para seu {$commerceType}.",
            "Seção de {$categoryName} com os melhores produtos do mercado.",
            "Encontre tudo de {$categoryName} para suas necessidades.",
            "Os melhores itens de {$categoryName} disponíveis em nosso {$commerceType}.",
            "Produtos selecionados de {$categoryName} com qualidade garantida."
        ];
        
        return $templates[array_rand($templates)];
    }
}
