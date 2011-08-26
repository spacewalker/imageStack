<?php
/**
 * Make stack of images from array of images.
 *
 * @author Vladimir Loginov <spacewalker88@gmail.com>
 *
 * @param array $images
 * @param int $width
 * @param int $height
 * @param int $angle
 * @return true
 */
function imagickStack($images, $width = 300, $height = 200, $angle = 30)
{
    $items = count($images);

    for ($i = 0; $i < $items; $i++) {
        $imagick = new Imagick();
        $imagick->readImage($images[$i]);
        $imagick->rotateImage(new ImagickPixel('none'), mt_rand(-$angle, $angle));
        $imagick->scaleImage($width, $height, true);
        $imagick->writeImage('final'.$i.'.png');
        $imagick->clear();
        $imagick->destroy();
    }

    for ($i = 0; $i < $items-1; $i++) {
        $images[$i] = new Imagick('final' . $i . '.png');
        $images[$i+1] = new Imagick('final' . ($i+1) . '.png');
        $width = max($images[$i]->getImageWidth(), $images[$i+1]->getImageWidth());
        $height = max($images[$i]->getImageHeight(), $images[$i+1]->getImageHeight());
        $back = new Imagick();
        $back->newPseudoImage($width, $height, "xc:black");
        $back->setImageOpacity(0);

        $back->setImageColorspace($images[$i]->getImageColorspace());
        $back->compositeImage(
            $images[$i],
            $images[$i]->getImageCompose(),
            ($width - $images[$i]->getImageWidth()) / 2,
            ($height - $images[$i]->getImageHeight()) / 2
        );

        $back->setImageColorspace($images[$i+1]->getImageColorspace());
        $back->compositeImage(
            $images[$i+1],
            $images[$i+1]->getImageCompose(),
            ($width - $images[$i+1]->getImageWidth()) / 2,
            ($height - $images[$i+1]->getImageHeight()) / 2
        );

        $back->writeImage('final' . ($i+1) . '.png');
        $back->clear();
        $back->destroy();
        $images[$i]->clear();
        $images[$i]->destroy();
    }

    return true;
}

$images = array(
    'my0.jpg',
    'my1.jpg',
    'my2.jpg',
    'my3.jpg',
    'my4.jpg',
    'my5.jpg',
    'my6.jpg',
    'my7.jpg',
    'my8.jpg',
    'my9.jpg'
    );

imagickStack($images, 400, 300);
