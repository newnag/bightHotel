# Thumb

A simple, local image only, thumbnail generation script written in PHP with a file based cache and optional browser based cache.

## Example Usage

Firstly, make sure your cache directory is writable, then access the script like so:

```html
<img src='thumb.php?src=./images/photo.jpg&size=400x300' />
```

## Query Parameters

<table>
    <tr>
        <th>Key</th>
        <th>Example Value</th>
        <th>Default</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>src</td>
        <td>./images/photo.jpg</td>
        <td></td>
        <td>Absolute path, relative path, or local URL to the source image. Remote URLs are not allowed</td>
    </tr>
    <tr>
        <td>size</td>
        <td>100, 100x200, 100x, x100, &lt;500</td>
        <td>100</td>
        <td>Width and/or height must be between 8 and 1500</td>
    </tr>
    <tr>
        <td>crop</td>
        <td>0 - 1</td>
        <td>1</td>
        <td>0 = Displays the entire image within the canvas<br />1 = Crop the image so that the entire canvas is used</td>
    </tr>
    <tr>
        <td>trim</td>
        <td>0 - 1</td>
        <td>0</td>
        <td>0 = Displays white space for unused canvas<br />1 = Removes any white space</td>
    </tr>
    <tr>
        <td>zoom</td>
        <td>0 - 1</td>
        <td>0</td>
        <td>For when the size of the canvas is larger than the original image size<br />0 = Will not enlarge image<br />1 = Enlarges image beyond the original image size</td>
    </tr>
    <tr>
        <td>align</td>
        <td>c, t, r, b, l, tl, tr, br, bl</td>
        <td>c</td>
        <td>Alignment of image when cropped</td>
    </tr>
    <tr>
        <td>sharpen</td>
        <td>0 - 100</td>
        <td>0</td>
        <td>Percentage strength of the image sharpness, based on the percentage midpoint of 12 (strong) and 28 (weak)</td>
    </tr>
    <tr>
        <td>gray</td>
        <td>0 - 1</td>
        <td>0</td>
        <td>0 = Displays resized image as normal<br />1 = Converts image to grayscale</td>
    </tr>
    <tr>
        <td>ignore</td>
        <td>0 - 1</td>
        <td>0</td>
        <td>0 = Displays resized image as normal<br />1 = Displays original image file with the animation present</td>
    </tr>
</table>

## Size Parameter

<table>
    <tr>
        <th>Value</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>250</td>
        <td>Creates a square image 250px in width and 250px in height</td>
    </tr>
    <tr>
        <td>250x400</td>
        <td>Creates an image 250px in width and 400px in height</td>
    </tr>
    <tr>
        <td>250x</td>
        <td>Creates an image with a width of 250px and the height will be automatically calculated to maintain the aspect ration of the original image</td>
    </tr>
    <tr>
        <td>x250</td>
        <td>Createse an image with a height of 250px and the width will be automatically calculated to maintain the aspect ration of the original image</td>
    </tr>
    <tr>
        <td>&lt;800</td>
        <td>Creates an image where the width or height does not exceed 800px. For landscape images the width will be 800px and the height will be automatically calculated, and for portrait images the height will be 800px and the width will be automatically calculated.</td>
    </tr>
</table>

## Automatic Orientation Correction

If you're dealing with images straight from a camera, some may contain [EXIF](http://en.wikipedia.org/wiki/Exchangeable_image_file_format) data which specifies the original orientation the image should be viewed at.

To enable this feature, change the constant `ADJUST_ORIENTATION` to `true`.

More information, and an in depth analysis of EXIF Orientation can be found [in this article](http://www.daveperrett.com/articles/2012/07/28/exif-orientation-handling-is-a-ghetto/) written by @[daveperrett](http://www.twitter.com/daveperrett)

## License

Thumb.php is licensed under the [MIT license](http://opensource.org/licenses/MIT), see [LICENSE.md](https://github.com/jamiebicknell/Thumb/blob/master/LICENSE.md) for details.