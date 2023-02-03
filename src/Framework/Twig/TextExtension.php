<?php

namespace Framework\Twig;

/**
 * Série d'extensions concernant les textes
 *
 * @package Framework\Twig
 */
class TextExtension extends \Twig_Extension
{

    /**
     * @return \Twig_SimpleFilter[]
     */
    public function getFilters(): array
    {
        return [
            //Creation du nouveau filtre nommé:excerpt qui va appeler la methode excerpt
            new \Twig_SimpleFilter('excerpt', [$this, 'excerpt'])
        ];
    }

    /**
     * Renvoie un extrait du contenu de l'article à l'ecran
     * @param string $content: le contenu
     * @param int $maxLength: La taille maximale à défaut vaut 100 caractere
     * @return string
     */
    public function excerpt(string $content, int $maxLength = 100): string
    {
//Si taille de la chaine est superieur a la taille maximale
        if (mb_strlen($content) > $maxLength) {
            //On va reduire le nbr de caractere de $content à $maxLength qui vaut 100
            //On coupe $content en segment à partir du caractere 0 jusqu'au caractere 100
            $excerpt = mb_substr($content, 0, $maxLength);
            //On treouve la position du dernier espace dans la chaine
            $lastSpace = mb_strrpos($excerpt, ' ');
            //On segmente de nouveau  $excerpt à partir de 0 jusqu'a la position du dernier espace trouvé que l'on returne avec trois points
            return mb_substr($excerpt, 0, $lastSpace) . '...';
        }
        return $content;
    }
}
