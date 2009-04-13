<?php

/**
 * Hoa Framework
 *
 *
 * @license
 *
 * GNU General Public License
 *
 * This file is part of Hoa Open Accessibility.
 * Copyright (c) 2007, 2008 Ivan ENDERLIN. All rights reserved.
 *
 * HOA Open Accessibility is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * HOA Open Accessibility is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with HOA Open Accessibility; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *
 * @category    Framework
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Filter_Default
 *
 */

/**
 * Hoa_Framework
 */
require_once 'Framework.php';

/**
 * Hoa_Stream_Filter_Exception
 */
import('Stream.Filter.Exception');

/**
 * Hoa_Stream_Bucket_Brigade
 */
import('Stream.Bucket.Brigade');

/**
 * Class Hoa_Stream_Filter_Default.
 *
 * Default filter. Force to implement some methods.
 * Actually, it extends the php_user_filter class.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2008 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Filter_Default
 */

abstract class Hoa_Stream_Filter_Default extends php_user_filter {

    /**
     * Filter processed successfully with data available in the out bucket
     * brigade.
     *
     * @const int
     */
    const PASS_ON          = PSFS_PASS_ON;

    /**
     * Filter processed successfully, however no data was available to return.
     * More data is required from the stream or prior filter.
     *
     * @const int
     */
    const FEED_ME          = PSFS_FEED_ME;

    /**
     * The filter experienced and unrecoverable error and cannot continue.
     *
     * @const int
     */
    const FATAL_ERROR      = PSFS_ERR_FATAL;

    /**
     * Regular read/write.
     *
     * @const int
     */
    const FLAG_NORMAL      = PSFS_FLAG_NORMAL;

    /**
     * An incremental flush.
     *
     * @const int
     */
    const FLAG_FLUSH_INC   = PSFS_FLAG_FLUSH_INC;

    /**
     * Final flush prior to closing.
     *
     * @const int
     */
    const FLAG_FLUSH_CLOSE = PSFS_FLAG_FLUSH_CLOSE;



    /**
     * Filter data.
     * This method is called whenever data is read from or written to the attach
     * stream.
     *
     * @access  public
     * @param   resource  $in           A resource pointing to a bucket brigade
     *                                  which contains one or more bucket
     *                                  objects containing data to be filtered.
     * @param   resource  $out          A resource pointing to a second bucket
     *                                  brigade into which your modified buckets
     *                                  should be replaced.
     * @param   int       &$consumed    Which must always be declared by
     *                                  reference, should be incremented by the
     *                                  length of the data which your filter
     *                                  reads in and alters.
     * @param   bool      $closing      If the stream is in the process of
     *                                  closing (and therefore this is the last
     *                                  pass through the filterchain), the
     *                                  closing parameter will be set to true.
     * @return  int
     */
    public function filter ( $in, $out, &$consumed, $closing ) {

        $iBucket = new Hoa_Stream_Bucket_Brigade($in);
        $oBucket = new Hoa_Stream_Bucket_Brigade($out);

        while(true !== $iBucket->eob()) {

            $consumed += $iBucket->getLength();
            $oBucket->append($iBucket);
        }

        unset($iBucket);
        unset($oBucket);

        return self::PASS_ON;
    }

    /**
     * Called during instanciation of the filter class object.
     *
     * @access  public
     * @return  bool
     */
    public function onCreate ( ) {

        return true;
    }

    /**
     * Called upon filter shutdown (typically, this is also during stream
     * shutdown), and is executed after the flush method is called.
     *
     * @access  public
     * @return  void
     */
    public function onClose ( ) { }

    /**
     * Set the filter name.
     *
     * @access  public
     * @param   string  $name    Filter name.
     * @return  string
     */
    public function setName ( $name ) {

        $old              = $this->filtername;
        $this->filtername = $name;

        return $old;
    }

    /**
     * Set the filter parameters.
     *
     * @access  public
     * @param   mixed   $parameters    Filter parameters.
     * @return  mixed
     */
    public function setParameters ( $parameters ) {

        $old          = $this->params;
        $this->params = $parameters;

        return $old;
    }

    /**
     * Get the filter name.
     *
     * @access  public
     * @return  string
     */
    public function getName ( ) {

        return $this->filtername;
    }

    /**
     * Get the filter parameters.
     *
     * @access  public
     * @return  mixed
     */
    public function getParameters ( ) {

        return $this->params;
    }

    /**
     * Get the stream resource being filtered.
     * Maybe available only during filter calls when the closing parameter is
     * set to false.
     *
     * @access  public
     * @return  resource
     */
    public function getStream ( ) {

        return $this->stream;
    }
}