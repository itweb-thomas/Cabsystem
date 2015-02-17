<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableTitles extends JTable

{

    /**

     * Constructor

     *

     * @param object Database connector object

     */

    function __construct(&$db)

    {

        parent::__construct('#__cabsystem_customer_titles', 'title_id', $db);

    }

}