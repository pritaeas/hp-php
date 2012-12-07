{* arrayTemplate.tpl *}

{* strip will remove all whitespace, reducing the output *}
{strip}

<!doctype html>

{* simple variable substitution *}
<html lang="{$LANGUAGE}">
    <head>
        {* variable substitution using an array with dot notation *}
        <title>{$HEAD.title}</title>
        <meta name="robots" content="{$HEAD.robots}" />
    </head>
    <body>
        <h1>{$BODY.title}</h1>
        <p>{$BODY.text}</p>

        <h2>{$TABLE.title}</h2>
        <table border="1">
        
            {* initialize a counter, each call automatically increments it *}
            {counter start=0 print=false}
            
            {* initialize the PREVIOUS variable *}
            {assign var="PREVIOUS" value=""}

            {* loop the data array, from points to the variable to use *}
            {* item defines the variable inside the loop *}
            {foreach from=$TABLE.data item=BOOK name=books}
            
                {* make every third row a different colour *}
                {if $smarty.foreach.books.iteration is div by 3}
                    <tr style="background-color:#eee">
                {else}
                    <tr>
                {/if}
            
                    {* make the list numbered using the counter *}
                    <td>{counter}</td>
                    
                    {* if the publisher is equal to the previous one, don't print it *}
                    {if $PREVIOUS == $BOOK.publisher}
                        <td>&nbsp;</td>
                    {else}
                        <td>{$BOOK.publisher}</td>
                    {/if}
                    
                    <td>{$BOOK.isbn}</td>
                    
                    {* trim the title to 40 characters and add dots *}
                    <td>{$BOOK.title|truncate:30:"...":true}</td>
                </tr>
                
                {* remember the publisher *}
                {assign var="PREVIOUS" value=$BOOK.publisher}

            {foreachelse}
                {* this is executed when the array is empty *}
                <tr>
                    <td>No information found.</td>
                </tr>
            {/foreach}
        </table>
        <p>
            {* show the date, use the date_format modifier to format *}
            Generated at:
            {$smarty.now|date_format:' %Y-%m-%d %H:%M:%S'}
        </p>
    </body>
</html>
{/strip}