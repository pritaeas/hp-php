<?php
    // using-smarty.php

    // prepare some data, a list of books
    // array of arrays as it can be returned from a query (ordered by publisher)
    $arrayData = array (
        array ('isbn' => '0201633612', 'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software', 'publisher' => 'Addison Wesley'),
        array ('isbn' => '0201485672', 'title' => 'Refactoring: Improving the Design of Existing Code', 'publisher' => 'Addison Wesley'),
        array ('isbn' => '0201791692', 'title' => 'SQL Performance Tuning', 'publisher' => 'Addison Wesley'),
        array ('isbn' => '0131429019', 'title' => 'The Art of UNIX Programming', 'publisher' => 'Addison Wesley'),
        array ('isbn' => '0321466756', 'title' => 'Why Software Sucks...and What You Can Do About It', 'publisher' => 'Addison Wesley'),
        array ('isbn' => '143023864X', 'title' => 'Pro HTML5 Programming, Second Edition', 'publisher' => 'Apress'),
        array ('isbn' => '0735619670', 'title' => 'Code Complete: A Practical Handbook of Software Construction, Second Edition', 'publisher' => 'Microsoft Press'),
        array ('isbn' => '0735624003', 'title' => 'Programming Microsoft LINQ (PRO-Developer)', 'publisher' => 'Microsoft Press'),
        array ('isbn' => '190481140X', 'title' => 'Smarty: PHP Template Programming and Applications', 'publisher' => 'Packt Publishing'),
        array ('isbn' => '0130224189', 'title' => 'Algorithms + Data Structures = Programs', 'publisher' => 'Prentice Hall')
    );

    // include the class
    include 'smarty/Smarty.class.php';

    // create a new object
    $smarty = new Smarty();

    // assign a simple string
    // first parameter is what you use in the template
    // second parameter is the value
    $smarty->assign('LANGUAGE', 'en');

    // assign an array
    // in the template you use the dot notation to access it
    $smarty->assign('HEAD', array ('title' => 'Smarty Demo', 'robots' => 'noarchive,nofollow,noindex'));

    // build an array, then assign it
    $title = 'A Simple Smarty Demo';
    $text = 'This will demo some simple ways of using the Smarty templating system.';
    $body = array ('title' => $title, 'text' => $text);
    $smarty->assign('BODY', $body);

    // assign the book information for the table
    $smarty->assign('TABLE', array ('title' => 'List of Books', 'data' => $arrayData));

    // fetch() returns the template result as a string
    $output = $smarty->fetch('using-smarty.tpl');

    // display() outputs the template result directly
    $smarty->display('using-smarty.tpl');
?>