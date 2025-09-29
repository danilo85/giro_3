<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PortfolioWork;
use App\Models\PortfolioCategory;
use App\Models\User;
use App\Models\Autor;
use App\Models\Cliente;
use App\Models\Orcamento;
use Illuminate\Support\Str;

class PortfolioWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = PortfolioCategory::all();
        $autores = Autor::all();
        $clientes = Cliente::all();
        $orcamentos = Orcamento::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Nenhum usuário encontrado. Execute UserSeeder primeiro.');
            return;
        }
        
        if ($categories->isEmpty()) {
            $this->command->warn('Nenhuma categoria de portfólio encontrada. Execute PortfolioCategorySeeder primeiro.');
            return;
        }

        $trabalhos = [
            [
                'title' => 'Site Institucional TechCorp',
                'description' => 'Desenvolvimento de site institucional responsivo para empresa de tecnologia',
                'content' => '<h2>Projeto Site TechCorp</h2><p>Desenvolvimento completo de site institucional para a empresa TechCorp, incluindo design responsivo, sistema de gerenciamento de conteúdo e otimização para mecanismos de busca.</p><h3>Funcionalidades Implementadas:</h3><ul><li>Design responsivo</li><li>Sistema CMS personalizado</li><li>Blog integrado</li><li>Formulário de contato</li><li>Otimização SEO</li></ul>',
                'client' => 'TechCorp Ltda',
                'project_url' => 'https://techcorp.example.com',
                'completion_date' => '2025-08-30',
                'technologies' => json_encode(["Laravel", "Vue.js", "Tailwind CSS", "MySQL"]),
                'featured_image' => 'portfolio/techcorp-site.jpg',
                'meta_title' => 'Site Institucional TechCorp - Desenvolvimento Web',
                'meta_description' => 'Desenvolvimento de site institucional responsivo para TechCorp',
                'status' => 'published',
                'is_featured' => true,
                'category_type' => 'desenvolvimento-web'
            ],
            [
                'title' => 'Identidade Visual Café Aroma',
                'description' => 'Criação de identidade visual completa para cafeteria artesanal',
                'content' => '<h2>Identidade Visual Café Aroma</h2><p>Desenvolvimento completo da identidade visual para a cafeteria Café Aroma, incluindo logotipo, paleta de cores, tipografia e aplicações em diversos materiais.</p><h3>Entregáveis:</h3><ul><li>Logotipo principal e variações</li><li>Manual de identidade visual</li><li>Papelaria corporativa</li><li>Cardápio e materiais promocionais</li><li>Sinalização interna</li></ul>',
                'client' => 'Café Aroma',
                'project_url' => null,
                'completion_date' => '2025-07-15',
                'technologies' => json_encode(["Adobe Illustrator", "Adobe Photoshop", "Adobe InDesign"]),
                'featured_image' => 'portfolio/cafe-aroma-brand.jpg',
                'meta_title' => 'Identidade Visual Café Aroma - Design Gráfico',
                'meta_description' => 'Criação de identidade visual completa para cafeteria artesanal',
                'status' => 'published',
                'is_featured' => true,
                'category_type' => 'design-grafico'
            ],
            [
                'title' => 'Campanha Digital Loja Fashion',
                'description' => 'Estratégia de marketing digital para e-commerce de moda',
                'content' => '<h2>Campanha Digital Fashion Store</h2><p>Desenvolvimento e execução de campanha de marketing digital para loja de moda online, incluindo estratégia de redes sociais, anúncios pagos e email marketing.</p><h3>Resultados Alcançados:</h3><ul><li>Aumento de 150% no engajamento</li><li>Crescimento de 80% nas vendas online</li><li>ROI de 300% em anúncios pagos</li><li>Base de email ampliada em 200%</li></ul>',
                'client' => 'Fashion Store',
                'project_url' => 'https://fashionstore.example.com',
                'completion_date' => '2025-09-10',
                'technologies' => json_encode(["Facebook Ads", "Google Ads", "Mailchimp", "Analytics"]),
                'featured_image' => 'portfolio/fashion-campaign.jpg',
                'meta_title' => 'Campanha Digital Fashion Store - Marketing',
                'meta_description' => 'Estratégia de marketing digital para e-commerce de moda',
                'status' => 'published',
                'is_featured' => false,
                'category_type' => 'marketing-digital'
            ],
            [
                'title' => 'Ensaio Corporativo Empresa ABC',
                'description' => 'Sessão fotográfica corporativa para equipe executiva',
                'content' => '<h2>Ensaio Corporativo ABC</h2><p>Sessão fotográfica profissional para a equipe executiva da Empresa ABC, incluindo fotos individuais, em grupo e do ambiente corporativo.</p><h3>Serviços Realizados:</h3><ul><li>Planejamento da sessão</li><li>Fotografia individual dos executivos</li><li>Fotos em grupo da equipe</li><li>Registro do ambiente corporativo</li><li>Edição e tratamento das imagens</li></ul>',
                'client' => 'Empresa ABC',
                'project_url' => null,
                'completion_date' => '2025-06-20',
                'technologies' => json_encode(["Canon EOS R5", "Adobe Lightroom", "Adobe Photoshop"]),
                'featured_image' => 'portfolio/abc-corporate.jpg',
                'meta_title' => 'Ensaio Corporativo ABC - Fotografia',
                'meta_description' => 'Sessão fotográfica corporativa para equipe executiva',
                'status' => 'published',
                'is_featured' => false,
                'category_type' => 'fotografia'
            ]
        ];

        // Criar trabalhos de portfólio
        foreach ($trabalhos as $trabalhoData) {
            // Selecionar dados aleatórios
            $user = $users->random();
            $categoria = $this->selecionarCategoria($categories, $trabalhoData['category_type'], $user->id);
            $autor = $autores->isNotEmpty() ? $autores->random() : null;
            $cliente = $clientes->isNotEmpty() ? $clientes->random() : null;
            $orcamento = $orcamentos->isNotEmpty() ? $orcamentos->random() : null;
            
            if (!$categoria) {
                $categoria = $categories->where('user_id', $user->id)->first();
            }
            
            if (!$categoria) {
                continue; // Pular se não houver categoria disponível
            }

            $slug = Str::slug($trabalhoData['title']) . '-' . $user->id;
            
            PortfolioWork::firstOrCreate(
                [
                    'slug' => $slug,
                    'user_id' => $user->id
                ],
                [
                    'title' => $trabalhoData['title'],
                    'description' => $trabalhoData['description'],
                    'content' => $trabalhoData['content'],
                    'client' => $trabalhoData['client'],
                    'project_url' => $trabalhoData['project_url'],
                    'completion_date' => $trabalhoData['completion_date'],
                    'technologies' => $trabalhoData['technologies'],
                    'featured_image' => $trabalhoData['featured_image'],
                    'meta_title' => $trabalhoData['meta_title'],
                    'meta_description' => $trabalhoData['meta_description'],
                    'status' => $trabalhoData['status'],
                    'is_featured' => $trabalhoData['is_featured'],
                    'portfolio_category_id' => $categoria->id,
                    'user_id' => $user->id,
                    'client_id' => $cliente?->id,
                    'orcamento_id' => $orcamento?->id,
                ]
            );
        }

        $this->command->info('Trabalhos de portfólio criados com sucesso!');
        $this->command->info('Total de trabalhos criados: ' . count($trabalhos));
    }
    
    /**
     * Selecionar categoria baseada no tipo do trabalho
     */
    private function selecionarCategoria($categories, $categoryType, $userId)
    {
        // Tentar encontrar categoria que contenha o tipo no slug
        $categoria = $categories->where('user_id', $userId)
                              ->filter(function($cat) use ($categoryType) {
                                  return str_contains($cat->slug, $categoryType);
                              })
                              ->first();
        
        if (!$categoria) {
            // Se não encontrar, pegar qualquer categoria do usuário
            $categoria = $categories->where('user_id', $userId)->first();
        }
        
        return $categoria;
    }
}