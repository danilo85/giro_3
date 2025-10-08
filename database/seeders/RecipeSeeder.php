<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\RecipeCategory;
use App\Models\Ingredient;
use Illuminate\Support\Facades\DB;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('recipe_ingredients')->delete();
        Recipe::query()->delete();
        
        // Get categories
        $doces = RecipeCategory::where('name', 'Doces')->first();
        $salgados = RecipeCategory::where('name', 'Salgados')->first();
        $massas = RecipeCategory::where('name', 'Massas')->first();
        $carnes = RecipeCategory::where('name', 'Carnes')->first();
        $sobremesas = RecipeCategory::where('name', 'Sobremesas')->first();
        $lanches = RecipeCategory::where('name', 'Lanches')->first();
        $vegetarianos = RecipeCategory::where('name', 'Vegetarianos')->first();

        $recipes = [
            [
                'name' => 'Brigadeiro Tradicional',
                'description' => 'O clássico doce brasileiro que não pode faltar em nenhuma festa. Cremoso, doce e irresistível.',
                'category_id' => $doces->id,
                'preparation_time' => 20,
                'servings' => 30,
                'image_path' => 'recipes/brigadeiro.jpg',
                'preparation_method' => "1. Em uma panela, misture o leite condensado, o chocolate em pó e a manteiga.\n\n2. Leve ao fogo médio e mexa constantemente até que a mistura desgrude do fundo da panela.\n\n3. Despeje em um prato untado com manteiga e deixe esfriar.\n\n4. Com as mãos untadas, faça bolinhas e passe no chocolate granulado.\n\n5. Coloque em forminhas de papel e sirva.",
                'ingredients' => [
                    ['name' => 'Leite condensado', 'quantity' => 395, 'unit' => 'g'],
                    ['name' => 'Chocolate em pó', 'quantity' => 50, 'unit' => 'g'],
                    ['name' => 'Manteiga', 'quantity' => 30, 'unit' => 'g'],
                    ['name' => 'Chocolate granulado', 'quantity' => 100, 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Lasanha à Bolonhesa',
                'description' => 'Uma deliciosa lasanha com molho bolonhesa caseiro, queijo derretido e massa al dente.',
                'category_id' => $massas->id,
                'preparation_time' => 90,
                'servings' => 8,
                'image_path' => 'recipes/lasanha.jpg',
                'preparation_method' => "1. Prepare o molho bolonhesa refogando a carne moída com cebola, alho e tomate.\n\n2. Cozinhe a massa da lasanha em água fervente com sal até ficar al dente.\n\n3. Em uma forma, faça camadas alternando massa, molho bolonhesa e queijo.\n\n4. Cubra com molho branco e queijo parmesão ralado.\n\n5. Leve ao forno a 180°C por 40 minutos até dourar.",
                'ingredients' => [
                    ['name' => 'Massa de lasanha', 'quantity' => 500, 'unit' => 'g'],
                    ['name' => 'Carne moída', 'quantity' => 500, 'unit' => 'g'],
                    ['name' => 'Molho de tomate', 'quantity' => 400, 'unit' => 'g'],
                    ['name' => 'Queijo mussarela', 'quantity' => 300, 'unit' => 'g'],
                    ['name' => 'Queijo parmesão', 'quantity' => 100, 'unit' => 'g'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'unidade'],
                    ['name' => 'Alho', 'quantity' => 3, 'unit' => 'dentes'],
                ]
            ],
            [
                'name' => 'Frango Grelhado',
                'description' => 'Peito de frango suculento e temperado, grelhado na perfeição. Uma opção saudável e saborosa.',
                'category_id' => $carnes->id,
                'preparation_time' => 45,
                'servings' => 4,
                'image_path' => 'recipes/frango_grelhado.jpg',
                'preparation_method' => "1. Tempere o frango com sal, pimenta, alho e ervas.\n\n2. Deixe marinar por pelo menos 30 minutos.\n\n3. Aqueça a grelha ou frigideira antiaderente.\n\n4. Grelhe o frango por 6-8 minutos de cada lado.\n\n5. Verifique se está bem cozido e sirva quente.",
                'ingredients' => [
                    ['name' => 'Peito de frango', 'quantity' => 800, 'unit' => 'g'],
                    ['name' => 'Alho', 'quantity' => 4, 'unit' => 'dentes'],
                    ['name' => 'Sal', 'quantity' => 5, 'unit' => 'g'],
                    ['name' => 'Pimenta-do-reino', 'quantity' => 2, 'unit' => 'g'],
                    ['name' => 'Azeite', 'quantity' => 30, 'unit' => 'ml'],
                    ['name' => 'Ervas finas', 'quantity' => 10, 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Bolo de Chocolate',
                'description' => 'Um bolo de chocolate fofinho e úmido, perfeito para qualquer ocasião especial.',
                'category_id' => $sobremesas->id,
                'preparation_time' => 60,
                'servings' => 12,
                'image_path' => 'recipes/bolo_chocolate.jpg',
                'preparation_method' => "1. Misture os ingredientes secos em uma tigela.\n\n2. Em outra tigela, bata os ovos com açúcar até ficar cremoso.\n\n3. Adicione o leite, óleo e chocolate derretido.\n\n4. Misture tudo delicadamente e despeje na forma untada.\n\n5. Asse a 180°C por 40-45 minutos.",
                'ingredients' => [
                    ['name' => 'Farinha de trigo', 'quantity' => 300, 'unit' => 'g'],
                    ['name' => 'Açúcar', 'quantity' => 250, 'unit' => 'g'],
                    ['name' => 'Chocolate em pó', 'quantity' => 80, 'unit' => 'g'],
                    ['name' => 'Ovos', 'quantity' => 3, 'unit' => 'unidades'],
                    ['name' => 'Leite', 'quantity' => 200, 'unit' => 'ml'],
                    ['name' => 'Óleo', 'quantity' => 100, 'unit' => 'ml'],
                    ['name' => 'Fermento em pó', 'quantity' => 10, 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Hambúrguer Artesanal',
                'description' => 'Hambúrguer caseiro com carne suculenta, pão artesanal e ingredientes frescos.',
                'category_id' => $lanches->id,
                'preparation_time' => 30,
                'servings' => 4,
                'image_path' => 'recipes/hamburguer.jpg',
                'preparation_method' => "1. Tempere a carne moída com sal, pimenta e cebola.\n\n2. Modele os hambúrgueres e grelhe por 4-5 minutos de cada lado.\n\n3. Torre levemente os pães.\n\n4. Monte o hambúrguer com alface, tomate, queijo e molhos.\n\n5. Sirva imediatamente.",
                'ingredients' => [
                    ['name' => 'Carne moída', 'quantity' => 600, 'unit' => 'g'],
                    ['name' => 'Pão de hambúrguer', 'quantity' => 4, 'unit' => 'unidades'],
                    ['name' => 'Queijo cheddar', 'quantity' => 4, 'unit' => 'fatias'],
                    ['name' => 'Alface', 'quantity' => 4, 'unit' => 'folhas'],
                    ['name' => 'Tomate', 'quantity' => 2, 'unit' => 'unidades'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'unidade'],
                ]
            ],
            [
                'name' => 'Salada Caesar',
                'description' => 'Salada clássica com alface crocante, croutons dourados e molho caesar cremoso.',
                'category_id' => $salgados->id,
                'preparation_time' => 20,
                'servings' => 4,
                'image_path' => 'recipes/salada_caesar.jpg',
                'preparation_method' => "1. Lave e corte a alface em pedaços grandes.\n\n2. Prepare os croutons tostando cubos de pão com azeite.\n\n3. Misture os ingredientes do molho caesar.\n\n4. Combine a alface com o molho e croutons.\n\n5. Finalize com queijo parmesão ralado.",
                'ingredients' => [
                    ['name' => 'Alface romana', 'quantity' => 2, 'unit' => 'pés'],
                    ['name' => 'Pão', 'quantity' => 4, 'unit' => 'fatias'],
                    ['name' => 'Queijo parmesão', 'quantity' => 50, 'unit' => 'g'],
                    ['name' => 'Maionese', 'quantity' => 100, 'unit' => 'g'],
                    ['name' => 'Mostarda', 'quantity' => 10, 'unit' => 'g'],
                    ['name' => 'Limão', 'quantity' => 1, 'unit' => 'unidade'],
                ]
            ],
            [
                'name' => 'Risotto de Camarão',
                'description' => 'Risotto cremoso com camarões frescos, um prato sofisticado e delicioso.',
                'category_id' => $massas->id,
                'preparation_time' => 45,
                'servings' => 4,
                'image_path' => 'recipes/risotto_camarao.jpg',
                'preparation_method' => "1. Refogue a cebola e alho no azeite.\n\n2. Adicione o arroz arbóreo e refogue por 2 minutos.\n\n3. Adicione o caldo quente aos poucos, mexendo sempre.\n\n4. Quando o arroz estiver al dente, adicione os camarões.\n\n5. Finalize com queijo parmesão e manteiga.",
                'ingredients' => [
                    ['name' => 'Arroz arbóreo', 'quantity' => 300, 'unit' => 'g'],
                    ['name' => 'Camarão', 'quantity' => 400, 'unit' => 'g'],
                    ['name' => 'Caldo de peixe', 'quantity' => 1000, 'unit' => 'ml'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'unidade'],
                    ['name' => 'Alho', 'quantity' => 3, 'unit' => 'dentes'],
                    ['name' => 'Vinho branco', 'quantity' => 100, 'unit' => 'ml'],
                    ['name' => 'Queijo parmesão', 'quantity' => 80, 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Pão de Açúcar',
                'description' => 'Pão doce tradicional, macio e levemente adocicado, perfeito para o café da manhã.',
                'category_id' => $doces->id,
                'preparation_time' => 120,
                'servings' => 8,
                'image_path' => 'recipes/pao_acucar.jpg',
                'preparation_method' => "1. Misture a farinha, açúcar, sal e fermento.\n\n2. Adicione os ovos, leite morno e manteiga derretida.\n\n3. Sove a massa até ficar lisa e elástica.\n\n4. Deixe crescer por 1 hora em local morno.\n\n5. Modele os pães e asse a 180°C por 25-30 minutos.",
                'ingredients' => [
                    ['name' => 'Farinha de trigo', 'quantity' => 500, 'unit' => 'g'],
                    ['name' => 'Açúcar', 'quantity' => 100, 'unit' => 'g'],
                    ['name' => 'Fermento biológico', 'quantity' => 10, 'unit' => 'g'],
                    ['name' => 'Ovos', 'quantity' => 2, 'unit' => 'unidades'],
                    ['name' => 'Leite', 'quantity' => 250, 'unit' => 'ml'],
                    ['name' => 'Manteiga', 'quantity' => 80, 'unit' => 'g'],
                    ['name' => 'Sal', 'quantity' => 5, 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Coxinha de Frango',
                'description' => 'O salgado mais amado do Brasil! Massa cremosa recheada com frango desfiado temperado.',
                'category_id' => $salgados->id,
                'preparation_time' => 90,
                'servings' => 20,
                'image_path' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400',
                'preparation_method' => "1. Cozinhe o frango e desfie. Tempere com cebola, alho, sal e pimenta.\n\n2. Prepare a massa com caldo de frango, farinha e manteiga.\n\n3. Cozinhe mexendo até formar uma massa lisa.\n\n4. Deixe esfriar, modele as coxinhas com o recheio.\n\n5. Passe na farinha, ovo e farinha de rosca. Frite até dourar.",
                'ingredients' => [
                    ['name' => 'Peito de frango', 'quantity' => 500, 'unit' => 'g'],
                    ['name' => 'Farinha de trigo', 'quantity' => 400, 'unit' => 'g'],
                    ['name' => 'Caldo de frango', 'quantity' => 500, 'unit' => 'ml'],
                    ['name' => 'Manteiga', 'quantity' => 50, 'unit' => 'g'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'unidade'],
                    ['name' => 'Alho', 'quantity' => 3, 'unit' => 'dentes'],
                    ['name' => 'Farinha de rosca', 'quantity' => 200, 'unit' => 'g'],
                    ['name' => 'Ovos', 'quantity' => 2, 'unit' => 'unidades'],
                ]
            ],
            [
                'name' => 'Feijoada Completa',
                'description' => 'O prato mais tradicional do Brasil! Feijão preto com carnes variadas, servido com acompanhamentos.',
                'category_id' => $carnes->id,
                'preparation_time' => 240,
                'servings' => 8,
                'image_path' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400',
                'preparation_method' => "1. Deixe o feijão de molho na véspera.\n\n2. Cozinhe o feijão com as carnes salgadas.\n\n3. Refogue a linguiça, bacon e carnes frescas.\n\n4. Junte tudo e cozinhe por mais 1 hora.\n\n5. Sirva com arroz, couve, farofa e laranja.",
                'ingredients' => [
                    ['name' => 'Feijão preto', 'quantity' => 500, 'unit' => 'g'],
                    ['name' => 'Linguiça calabresa', 'quantity' => 300, 'unit' => 'g'],
                    ['name' => 'Bacon', 'quantity' => 200, 'unit' => 'g'],
                    ['name' => 'Costela de porco', 'quantity' => 400, 'unit' => 'g'],
                    ['name' => 'Carne seca', 'quantity' => 200, 'unit' => 'g'],
                    ['name' => 'Cebola', 'quantity' => 2, 'unit' => 'unidades'],
                    ['name' => 'Alho', 'quantity' => 6, 'unit' => 'dentes'],
                    ['name' => 'Louro', 'quantity' => 3, 'unit' => 'folhas'],
                ]
            ],
            [
                'name' => 'Moqueca de Peixe',
                'description' => 'Prato típico da Bahia com peixe, leite de coco, dendê e temperos especiais.',
                'category_id' => $carnes->id,
                'preparation_time' => 45,
                'servings' => 4,
                'image_path' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400',
                'preparation_method' => "1. Tempere o peixe com limão, sal e pimenta.\n\n2. Refogue cebola, tomate e pimentão.\n\n3. Adicione o leite de coco e o dendê.\n\n4. Coloque o peixe e cozinhe por 15 minutos.\n\n5. Finalize com coentro e sirva com pirão.",
                'ingredients' => [
                    ['name' => 'Peixe (robalo ou badejo)', 'quantity' => 800, 'unit' => 'g'],
                    ['name' => 'Leite de coco', 'quantity' => 400, 'unit' => 'ml'],
                    ['name' => 'Dendê', 'quantity' => 50, 'unit' => 'ml'],
                    ['name' => 'Tomate', 'quantity' => 2, 'unit' => 'unidades'],
                    ['name' => 'Pimentão', 'quantity' => 1, 'unit' => 'unidade'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'unidade'],
                    ['name' => 'Coentro', 'quantity' => 50, 'unit' => 'g'],
                    ['name' => 'Limão', 'quantity' => 2, 'unit' => 'unidades'],
                ]
            ],
            [
                'name' => 'Pudim de Leite Condensado',
                'description' => 'Sobremesa clássica brasileira, cremosa e com calda de caramelo dourada.',
                'category_id' => $sobremesas->id,
                'preparation_time' => 180,
                'servings' => 8,
                'image_path' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=400',
                'preparation_method' => "1. Faça o caramelo com açúcar e água até dourar.\n\n2. Despeje na forma e deixe esfriar.\n\n3. Bata no liquidificador leite condensado, leite e ovos.\n\n4. Despeje sobre o caramelo e asse em banho-maria.\n\n5. Asse por 1 hora a 180°C. Deixe esfriar e desenforme.",
                'ingredients' => [
                    ['name' => 'Leite condensado', 'quantity' => 395, 'unit' => 'g'],
                    ['name' => 'Leite', 'quantity' => 400, 'unit' => 'ml'],
                    ['name' => 'Ovos', 'quantity' => 3, 'unit' => 'unidades'],
                    ['name' => 'Açúcar', 'quantity' => 200, 'unit' => 'g'],
                    ['name' => 'Água', 'quantity' => 100, 'unit' => 'ml'],
                ]
            ],
            [
                'name' => 'Beijinho de Coco',
                'description' => 'Doce tradicional de festa, feito com leite condensado e coco ralado.',
                'category_id' => $doces->id,
                'preparation_time' => 25,
                'servings' => 25,
                'image_path' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400',
                'preparation_method' => "1. Misture leite condensado, coco ralado e manteiga.\n\n2. Leve ao fogo mexendo até desgrudar da panela.\n\n3. Deixe esfriar em prato untado.\n\n4. Faça bolinhas e passe no coco ralado.\n\n5. Coloque em forminhas e decore com cravo.",
                'ingredients' => [
                    ['name' => 'Leite condensado', 'quantity' => 395, 'unit' => 'g'],
                    ['name' => 'Coco ralado', 'quantity' => 150, 'unit' => 'g'],
                    ['name' => 'Manteiga', 'quantity' => 30, 'unit' => 'g'],
                    ['name' => 'Coco ralado para cobertura', 'quantity' => 100, 'unit' => 'g'],
                    ['name' => 'Cravo da índia', 'quantity' => 25, 'unit' => 'unidades'],
                ]
            ],
            [
                'name' => 'Pastel de Feira',
                'description' => 'Salgado crocante e dourado, recheado com carne moída temperada.',
                'category_id' => $salgados->id,
                'preparation_time' => 60,
                'servings' => 15,
                'image_path' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400',
                'preparation_method' => "1. Prepare a massa com farinha, água, sal e óleo.\n\n2. Deixe descansar por 30 minutos.\n\n3. Refogue a carne com cebola, alho e temperos.\n\n4. Abra a massa, recheie e feche as bordas.\n\n5. Frite em óleo quente até dourar.",
                'ingredients' => [
                    ['name' => 'Farinha de trigo', 'quantity' => 400, 'unit' => 'g'],
                    ['name' => 'Carne moída', 'quantity' => 300, 'unit' => 'g'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'unidade'],
                    ['name' => 'Alho', 'quantity' => 3, 'unit' => 'dentes'],
                    ['name' => 'Óleo', 'quantity' => 100, 'unit' => 'ml'],
                    ['name' => 'Água', 'quantity' => 200, 'unit' => 'ml'],
                    ['name' => 'Sal', 'quantity' => 5, 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Tapioca Recheada',
                'description' => 'Iguaria nordestina feita com goma de tapioca, leve e versátil.',
                'category_id' => $lanches->id,
                'preparation_time' => 15,
                'servings' => 4,
                'image_path' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400',
                'preparation_method' => "1. Peneire a goma de tapioca para quebrar os grumos.\n\n2. Aqueça uma frigideira antiaderente.\n\n3. Espalhe a goma formando um círculo.\n\n4. Quando firmar, adicione o recheio de um lado.\n\n5. Dobre ao meio e sirva quente.",
                'ingredients' => [
                    ['name' => 'Goma de tapioca', 'quantity' => 200, 'unit' => 'g'],
                    ['name' => 'Queijo coalho', 'quantity' => 150, 'unit' => 'g'],
                    ['name' => 'Presunto', 'quantity' => 100, 'unit' => 'g'],
                    ['name' => 'Tomate', 'quantity' => 1, 'unit' => 'unidade'],
                    ['name' => 'Orégano', 'quantity' => 2, 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Açaí na Tigela',
                'description' => 'Sobremesa refrescante e nutritiva, típica do Norte do Brasil.',
                'category_id' => $sobremesas->id,
                'preparation_time' => 10,
                'servings' => 2,
                'image_path' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400',
                'preparation_method' => "1. Bata a polpa de açaí com banana e guaraná.\n\n2. A consistência deve ficar cremosa.\n\n3. Sirva em tigelas geladas.\n\n4. Decore com granola, banana e mel.\n\n5. Adicione outros complementos a gosto.",
                'ingredients' => [
                    ['name' => 'Polpa de açaí', 'quantity' => 400, 'unit' => 'g'],
                    ['name' => 'Banana', 'quantity' => 2, 'unit' => 'unidades'],
                    ['name' => 'Guaraná', 'quantity' => 100, 'unit' => 'ml'],
                    ['name' => 'Granola', 'quantity' => 100, 'unit' => 'g'],
                    ['name' => 'Mel', 'quantity' => 50, 'unit' => 'ml'],
                ]
            ],
            [
                'name' => 'Pão de Queijo Mineiro',
                'description' => 'Quitanda mineira irresistível, crocante por fora e macia por dentro.',
                'category_id' => $lanches->id,
                'preparation_time' => 45,
                'servings' => 20,
                'image_path' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400',
                'preparation_method' => "1. Ferva água, leite, óleo e sal.\n\n2. Despeje sobre o polvilho e misture bem.\n\n3. Deixe esfriar e adicione ovos e queijo.\n\n4. Faça bolinhas e coloque na assadeira.\n\n5. Asse a 200°C por 20-25 minutos.",
                'ingredients' => [
                    ['name' => 'Polvilho doce', 'quantity' => 250, 'unit' => 'g'],
                    ['name' => 'Polvilho azedo', 'quantity' => 250, 'unit' => 'g'],
                    ['name' => 'Queijo minas', 'quantity' => 200, 'unit' => 'g'],
                    ['name' => 'Ovos', 'quantity' => 3, 'unit' => 'unidades'],
                    ['name' => 'Leite', 'quantity' => 250, 'unit' => 'ml'],
                    ['name' => 'Óleo', 'quantity' => 100, 'unit' => 'ml'],
                    ['name' => 'Sal', 'quantity' => 5, 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Quindim',
                'description' => 'Doce tradicional português-brasileiro, feito com gemas e coco.',
                'category_id' => $sobremesas->id,
                'preparation_time' => 90,
                'servings' => 12,
                'image_path' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400',
                'preparation_method' => "1. Bata as gemas com açúcar até clarear.\n\n2. Adicione coco ralado e manteiga derretida.\n\n3. Unte forminhas com manteiga e açúcar.\n\n4. Distribua a mistura nas forminhas.\n\n5. Asse em banho-maria por 40 minutos.",
                'ingredients' => [
                    ['name' => 'Gemas de ovo', 'quantity' => 12, 'unit' => 'unidades'],
                    ['name' => 'Açúcar', 'quantity' => 300, 'unit' => 'g'],
                    ['name' => 'Coco ralado', 'quantity' => 200, 'unit' => 'g'],
                    ['name' => 'Manteiga', 'quantity' => 50, 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Bobó de Camarão',
                'description' => 'Prato baiano cremoso feito com camarão, mandioca e leite de coco.',
                'category_id' => $carnes->id,
                'preparation_time' => 60,
                'servings' => 6,
                'image_path' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400',
                'preparation_method' => "1. Cozinhe a mandioca até ficar macia.\n\n2. Amasse e misture com leite de coco.\n\n3. Refogue o camarão com alho, cebola e dendê.\n\n4. Misture com o purê de mandioca.\n\n5. Tempere e sirva com arroz branco.",
                'ingredients' => [
                    ['name' => 'Camarão médio', 'quantity' => 500, 'unit' => 'g'],
                    ['name' => 'Mandioca', 'quantity' => 800, 'unit' => 'g'],
                    ['name' => 'Leite de coco', 'quantity' => 400, 'unit' => 'ml'],
                    ['name' => 'Dendê', 'quantity' => 30, 'unit' => 'ml'],
                    ['name' => 'Cebola', 'quantity' => 1, 'unit' => 'unidade'],
                    ['name' => 'Alho', 'quantity' => 4, 'unit' => 'dentes'],
                    ['name' => 'Coentro', 'quantity' => 30, 'unit' => 'g'],
                ]
            ],
        ];

        foreach ($recipes as $recipeData) {
            // Create recipe
            $recipe = Recipe::create([
                'user_id' => 1,
                'category_id' => $recipeData['category_id'],
                'name' => $recipeData['name'],
                'description' => $recipeData['description'],
                'preparation_method' => $recipeData['preparation_method'],
                'preparation_time' => $recipeData['preparation_time'],
                'servings' => $recipeData['servings'],
                'image_path' => $recipeData['image_path'],
                'is_active' => true,
            ]);

            // Create ingredients for this recipe
            foreach ($recipeData['ingredients'] as $ingredientData) {
                // Find or create ingredient
                $ingredient = Ingredient::firstOrCreate([
                    'name' => $ingredientData['name']
                ]);

                // Create recipe_ingredient relationship
                DB::table('recipe_ingredients')->insert([
                    'recipe_id' => $recipe->id,
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $ingredientData['quantity'],
                    'unit' => $ingredientData['unit'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Receitas criadas com sucesso!');
    }
}