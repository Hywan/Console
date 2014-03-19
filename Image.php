<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2014, Ivan Enderlin. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\Console {

/**
 * Class \Hoa\Console\Image.
 *
 * Allow to manipulate image in a terminal.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2014 Ivan Enderlin.
 * @license    New BSD License
 */

class Image {

    protected $_image = null;
    protected $_name = null;
    protected $_width = 'auto';
    protected $_height = 'auto';



    public function __construct ( \Hoa\Stream\IStream\In $image ) {

        $this->setImage($image);

        return;
    }

    protected function setImage ( \Hoa\Stream\IStream\In $image ) {

        $old          = $this->_image;
        $this->_image = $image;

        if($image instanceof \Hoa\Stream\IStream\Pathable)
            $name = $image->getBasename();
        else
            $name = basename($image->getStreamName());

        $this->setName($name);

        return $old;
    }

    public function getImage ( ) {

        return $this->_image;
    }

    public function setName ( $name ) {

        $old         = $this->_name;
        $this->_name = $name;

        return $old;
    }

    public function getName ( ) {

        return $this->_name;
    }

    public function setSize ( $width, $height ) {

        $this->setWidth($width);
        $this->setHeight($height);

        return;
    }

    public function setWidth ( $width ) {

        $old         = $this->_width;
        $this->_width = $width;

        return $old;
    }

    public function getWidth ( ) {

        return $this->_width;
    }

    public function setHeight ( $height ) {

        $old         = $this->_height;
        $this->_height = $height;

        return $old;
    }

    public function getHeight ( ) {

        return $this->_height;
    }

    protected function _format ( $inline = true ) {

        $image   = $this->getImage();
        $content = $image->readAll();
        $name    = base64_encode($this->getName());

        if($image instanceof \Hoa\Stream\IStream\Statable)
            $size = $this->getImage()->getSize();
        else
            $size = strlen($content);

        $width   = $this->getWidth();
        $height  = $this->getHeight();
        $content = base64_encode($content);

        return "\033]1337;File=" .
               'name' . $name . ';' .
               'size=' . $size . ';' .
               'inline=' . ((int) $inline) . ';' .
               'width=' . $width . ';' .
               'height=' . $height . ':' .
               $content .
               "\007\n";
    }

    public function download ( ) {

        return $this->_format(false);
    }

    public function __toString ( ) {

        return $this->_format(true);
    }
}

}
