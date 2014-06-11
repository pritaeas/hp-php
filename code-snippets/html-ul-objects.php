<?php
class MenuItem
{
    public $Id = NULL;
    public $Parent = NULL;
    public $Name = '';
    public $Children = array ();
}

$html = <<<EOT
<ul>
    <li>menu 1</li>
    <li>menu 2<ul>
        <li>menu 2.1<ul>
            <li>menu 2.1.1</li>
            <li>menu 2.1.2</li>
            <li>menu 2.1.3</li></ul></li>
        <li>menu 2.2</li>
        <li>menu 2.3</li></ul></li>
    <li>menu 3</li>
</ul>
EOT;

function find_ul_or_li($source)
{
    $result = false;

    $pos_ul = strpos($source, '<ul>');
    $pos_li = strpos($source, '<li>');
    $pos_cul = strpos($source, '</ul>');
    $pos_cli = strpos($source, '</li>');

    if ($pos_ul === false)
        $pos_ul = PHP_INT_MAX;

    if ($pos_li === false)
        $pos_li = PHP_INT_MAX;

    if ($pos_cul === false)
        $pos_cul = PHP_INT_MAX;

    if ($pos_cli === false)
        $pos_cli = PHP_INT_MAX;

    if ($pos_ul < min($pos_li, $pos_cul, $pos_cli))
        $result = array ('token' => 'ul', 'pos' => $pos_ul);
    else if ($pos_li < min($pos_ul, $pos_cul, $pos_cli))
        $result = array ('token' => 'li', 'pos' => $pos_li);
    else if ($pos_cul < min($pos_ul, $pos_li, $pos_cli))
        $result = array ('token' => 'cul', 'pos' => $pos_cul);
    else if ($pos_cli < min($pos_ul, $pos_li, $pos_cul))
        $result = array ('token' => 'cli', 'pos' => $pos_cli);

    return $result;
}

function ul_to_objects($source)
{
    $menu = new MenuItem();
    $menu->Name = 'root';

    $tableInfo = array ();

    $currentItem = $menu;
    $currentId = 1;

    $done = false;
    $previous_token = '';

    while (!$done)
    {
        $pos_result = find_ul_or_li($source);
        if (!$pos_result)
        {
            $done = true;
            continue;
        }

        if ($pos_result['token'] == 'ul')
        {
            if ($previous_token == 'li')
            {
                $value = substr($source, 0, $pos_result['pos']);

                $newItem = new MenuItem();
                $newItem->Id = $currentId;
                $newItem->Name = $value;
                $newItem->Parent = $currentItem;

                $tableInfo[] = array ('id' => $currentId, 'parent' => $currentItem->Id, 'content' => $value);

                $currentItem->Children[] = $newItem;
                $currentItem = $newItem;

                $currentId++;
            }

            $source = substr($source, $pos_result['pos'] + 4);
            $previous_token = 'ul';
        }
        else if ($pos_result['token'] == 'li')
        {
            $source = substr($source, $pos_result['pos'] + 4);
            $previous_token = 'li';
        }
        else if ($pos_result['token'] == 'cul')
        {
            $currentItem = $currentItem->Parent;

            $source = substr($source, $pos_result['pos'] + 5);
            $previous_token = 'cul';
        }
        else if ($pos_result['token'] == 'cli')
        {
            $value = substr($source, 0, $pos_result['pos']);
            if ($value != '')
            {
                $newItem = new MenuItem();
                $newItem->Id = $currentId;
                $newItem->Name = $value;
                $newItem->Parent = $currentItem;

                $tableInfo[] = array ('id' => $currentId, 'parent' => $currentItem->Id, 'content' => $value);

                $currentItem->Children[] = $newItem;

                $currentId++;
            }

            $source = substr($source, $pos_result['pos'] + 5);
            $previous_token = 'cli';
        }
    }

    //return $menu;
    return $tableInfo;
}

$menu = ul_to_objects($html);
print_r($menu);
?>