<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shotonoff
 * Date: 8/10/12
 * Time: 10:10 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

/**
 * @Entity
 * @Table(name="doma")
 */
class Doma
{
    /**
     * @Column(type="string")
     */
    protected $name;
    /**
     * @Column(type="string")
     */
    protected $korp;
    /**
     * @Column(type="string")
     */
    protected $socr;
    /**
     * @id
     * @Column(type="string")
     */
    protected $code;
    /**
     * @Column(type="string", length=25, nullable=true)
     */
    protected $index;
    /**
     * @Column(type="string",  length=25, nullable=true)
     */
    protected $gninmb;
    /**
     * @Column(type="string")
     */
    protected $uno;
    /**
     * @Column(type="string")
     */
    protected $ocatd;
}