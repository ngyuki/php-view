# memo

次のようにブロックをクロージャーで表現するアイデア。

```php
<?php $this->block('title', 'this is title') ?>

<?php $this->block('content', function(){ ?>

     <?= $this->parent() ?>
    
    this is content

<?php }) ?>
```

シンタックスが書きにくいのと変数を明示的に use するか extract しないといけないのが辛い？

変数は $this->value のように参照することにすれば大丈夫？

---

普通にクラスで書くとか。

```php
<?php
class HogeView extends LayoutView
{
    function title()
    {
        ?>
            this is title
        <?
    }

    function content()
    {
        ?>
            this is content
        <?
    }
}

class LayoutView
{
    function render()
    {
        ?>
            <html>
                <head>
                    <?= $this->title() ?>
                </head>
                <body>
                    <?= $this->content() ?>
                </body>
            </html>
        <?
    }

    function title()
    {
        ?>
            this is title
        <?
    }
}
?>
```
