<?php
/**
 * @package     News Carousel UNPA
 * @subpackage  mod_news_carousel
 * @copyright   Copyright (C) 2025 UNPA UARG. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
?>

<!-- Carrusel de Noticias UNPA -->
<div id="<?php echo $moduleId; ?>" class="news-carousel-container">
    <div class="container-fluid">
        
        <?php if ($module->showtitle) : ?>
            <h2 class="news-carousel-title">
                <?php echo htmlspecialchars($module->title, ENT_QUOTES, 'UTF-8'); ?>
            </h2>
        <?php endif; ?>
        
        <div class="swiper news-swiper">
            <div class="swiper-wrapper">
                
                <?php foreach ($processedArticles as $index => $article) : ?>
                    <div class="swiper-slide">
                        <div class="news-slide" data-article-id="<?php echo $article['id']; ?>">
                            
                            <!-- Imagen de fondo -->
                            <div class="news-slide-bg" style="background-image: url('<?php echo htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8'); ?>');"></div>
                            
                            <!-- Overlay con degradado -->
                            <div class="news-slide-overlay"></div>
                            
                            <!-- Contenido de la noticia -->
                            <div class="news-slide-content">
                                
                                <!-- Título del artículo -->
                                <h3 class="news-slide-title">
                                    <?php echo $article['title']; ?>
                                </h3>
                                
                                <!-- Excerpt/Resumen -->
                                <?php if (!empty($article['excerpt'])) : ?>
                                    <p class="news-slide-excerpt">
                                        <?php echo htmlspecialchars($article['excerpt'], ENT_QUOTES, 'UTF-8'); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <!-- Fecha de publicación -->
                                <?php if ($showDate && !empty($article['date'])) : ?>
                                    <div class="news-slide-date">
                                        <?php echo htmlspecialchars($article['date'], ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Botón Leer más -->
                                <?php if ($showReadMore) : ?>
                                    <a href="<?php echo $article['url']; ?>" 
                                       class="news-read-more"
                                       title="Leer: <?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                       aria-label="Leer artículo completo: <?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                                        Leer más
                                    </a>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
            </div>
            
            <!-- Navegación con flechas -->
            <?php if ($showNavigation) : ?>
                <div class="swiper-button-next" aria-label="Siguiente noticia"></div>
                <div class="swiper-button-prev" aria-label="Noticia anterior"></div>
            <?php endif; ?>
            
            <!-- Paginación -->
            <?php if ($showPagination) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
            
        </div>
        
        <!-- Información adicional (solo visible para administradores) -->
        <?php if (JDEBUG || Factory::getUser()->authorise('core.admin')) : ?>
            <div class="news-carousel-debug" style="margin-top: 20px; padding: 10px; background: #f0f0f0; border-radius: 5px; font-size: 12px; color: #666;">
                <strong>📊 Info del módulo:</strong> 
                Mostrando <?php echo count($processedArticles); ?> de <?php echo $numArticles; ?> noticias solicitadas | 
                Categoría: <?php echo htmlspecialchars($categoryAlias, ENT_QUOTES, 'UTF-8'); ?> | 
                ID del módulo: <?php echo $module->id; ?>
                <?php if (!empty($processedArticles)) : ?>
                    | Última noticia: <?php echo htmlspecialchars($processedArticles[0]['title'], ENT_QUOTES, 'UTF-8'); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<!-- Script de inicialización específico del módulo (ya incluido en mod_news_carousel.php) -->

<!-- Datos estructurados para SEO -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "<?php echo htmlspecialchars($module->title ?: 'Noticias UNPA UARG', ENT_QUOTES, 'UTF-8'); ?>",
    "description": "Últimas noticias de la Universidad Nacional de la Patagonia Austral - Unidad Académica Río Gallegos",
    "itemListElement": [
        <?php foreach ($processedArticles as $index => $article) : ?>
        {
            "@type": "ListItem",
            "position": <?php echo $index + 1; ?>,
            "item": {
                "@type": "Article",
                "headline": "<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>",
                "description": "<?php echo htmlspecialchars($article['excerpt'], ENT_QUOTES, 'UTF-8'); ?>",
                "url": "<?php echo $article['url']; ?>",
                "datePublished": "<?php echo date('c', strtotime($article['created'])); ?>",
                "publisher": {
                    "@type": "Organization",
                    "name": "UNPA UARG",
                    "url": "https://www.uarg.unpa.edu.ar"
                }
            }
        }<?php echo ($index < count($processedArticles) - 1) ? ',' : ''; ?>
        <?php endforeach; ?>
    ]
}
</script>

<!-- Preload de la primera imagen para mejor rendimiento -->
<?php if (!empty($processedArticles[0]['image'])) : ?>
    <link rel="preload" as="image" href="<?php echo htmlspecialchars($processedArticles[0]['image'], ENT_QUOTES, 'UTF-8'); ?>">
<?php endif; ?>

<!-- Fallback para navegadores sin JavaScript -->
<noscript>
    <div class="news-carousel-fallback" style="display: block; margin: 20px 0;">
        <h3 style="color: var(--carousel-title-color, #1e3a8a); text-align: center; margin-bottom: 20px;">
            <?php echo htmlspecialchars($module->title ?: 'Últimas Noticias', ENT_QUOTES, 'UTF-8'); ?>
        </h3>
        
        <div style="display: grid; gap: 20px;">
            <?php foreach (array_slice($processedArticles, 0, 3) as $article) : ?>
                <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; background: #fff;">
                    <?php if (!empty($article['image'])) : ?>
                        <img src="<?php echo htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                             alt="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>"
                             style="width: 100%; height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    
                    <div style="padding: 15px;">
                        <h4 style="margin: 0 0 10px 0; color: var(--carousel-title-color, #1e3a8a);">
                            <a href="<?php echo $article['url']; ?>" style="text-decoration: none; color: inherit;">
                                <?php echo $article['title']; ?>
                            </a>
                        </h4>
                        
                        <?php if (!empty($article['excerpt'])) : ?>
                            <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">
                                <?php echo htmlspecialchars($article['excerpt'], ENT_QUOTES, 'UTF-8'); ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($showDate && !empty($article['date'])) : ?>
                            <small style="color: #999;">
                                <?php echo htmlspecialchars($article['date'], ENT_QUOTES, 'UTF-8'); ?>
                            </small>
                        <?php endif; ?>
                        
                        <?php if ($showReadMore) : ?>
                            <br><br>
                            <a href="<?php echo $article['url']; ?>" 
                               style="display: inline-block; background: var(--carousel-title-color, #1e3a8a); color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 14px;">
                                Leer más
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </noscript>
</div>