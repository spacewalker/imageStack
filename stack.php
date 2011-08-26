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

    $imagick[0] = new Imagick();
    $imagick[0]->readImage($images[0]);
    $imagick[0]->scaleImage($width, $height, true);
    $imagick[0]->rotateImage(
        new ImagickPixel('none'),
        !mt_rand(-$angle, $angle)?mt_rand(-$angle, $angle)+$angle/2:mt_rand(-$angle, $angle)
    );

    for ($i = 0; $i < $items-1; $i++) {
        $imagick[$i+1] = new Imagick();
        $imagick[$i+1]->readImage($images[$i+1]);
        $imagick[$i+1]->scaleImage($width, $height, true);
        $imagick[$i+1]->rotateImage(new ImagickPixel('none'), mt_rand(-$angle, $angle));

        $image_width = max($imagick[$i]->getImageWidth(), $imagick[$i+1]->getImageWidth());
        $image_height = max($imagick[$i]->getImageHeight(), $imagick[$i+1]->getImageHeight());

        $back = new Imagick();
        $back->newPseudoImage($image_width, $image_height, "xc:black");
        $back->setImageOpacity(0);

        $back->setImageColorspace($imagick[$i]->getImageColorspace());
        $back->compositeImage(
            $imagick[$i],
            $imagick[$i]->getImageCompose(),
            ($image_width - $imagick[$i]->getImageWidth()) / 2,
            ($image_height - $imagick[$i]->getImageHeight()) / 2
        );

        $back->setImageColorspace($imagick[$i+1]->getImageColorspace());
        $back->compositeImage(
            $imagick[$i+1],
            $imagick[$i+1]->getImageCompose(),
            ($image_width - $imagick[$i+1]->getImageWidth()) / 2,
            ($image_height - $imagick[$i+1]->getImageHeight()) / 2
        );

        $imagick[$i+1] = $back;
    }
    $back->scaleImage($width, $height, true);
    $back->writeImage('final.png');
    $back->clear();
    $back->destroy();

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
