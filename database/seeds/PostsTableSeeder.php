<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use GrahamCampbell\Markdown\Facades\Markdown;


class PostsTableSeeder extends Seeder
{

    function output($string)
    {
        return "\n" . $string . "\n";
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset posts table
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('content')->truncate();
        DB::table('content_tag')->truncate();
        DB::table('content_category')->truncate();

        $faker = Faker\Factory::create('en_US');
        $faker->addProvider(new \DavidBadura\FakerMarkdownGenerator\FakerProvider($faker));
        $date = Carbon::create(2017, 3, 6, 9);

            // App\Models\Core\Blog\Post::create([
            //     'user_id' => 1,
            //     'title' => 'Markdown supported',
            //     'excerpt' => $faker->sentence(rand(40, 60)),
            //     'body' => $this->firstPostBody,
            //     'markdown' => $this->markdownBody,
            //     'slug' => $faker->slug(),
            //     'created_at' => Carbon::create(2018, 3, 6, 9),
            //     'updated_at' => Carbon::create(2018, 3, 6, 9),
            //     'published_at' => Carbon::create(2018, 3, 6, 9),
            //     'published' => true,
            //     'views' => rand(1, 10) * rand(5, 10),
            // ]);

        for ($i=0; $i < 8; $i++) {
            $date->addHours(1);
            $publishedDate = clone($date);
            $published_at = $i > 5 && rand(0, 1) == 0 ? NULL : $publishedDate->addHours($i + rand(4, 20));
            $published = false;
            if($published_at)
                $published = true;

            $markdown = $faker->realText(300) ."\n" . $faker->markdownBulletedList() ."\n" . $faker->markdownH3() ."\n" . $faker->realText(350) ."\n" .
            $faker->markdownInlineImg('img-responsive');
            // $body = Markdown::convertToHtml($markdown);

            App\Models\Core\Blog\Post::create([
                'user_id' => rand(1,5),
                'title' => $faker->realText(100),
                // 'excerpt' => $faker->sentence(rand(40, 60)),
                'body' => $markdown,
                'slug' => $faker->slug(),
                'created_at' => clone($date),
                'updated_at' => clone($date),
                'published_at' => $published_at,
                'status' => 1,
                'views' => rand(1, 10) * rand(5, 10),
            ]);
        }

            // for ($i=1; $i <= 50; $i++) {
            //     App\Models\Core\Blog\PostCategory::create([
            //         'post_id' => $i,
            //         'category_id' => rand(1, 5),
            //     ]);
            // }
            //
            // for ($i=1; $i <= 50; $i++) {
            //     if(rand(0, 1)) {
            //         App\Models\Core\Blog\PostTag::create([
            //             'post_id' => $i,
            //             'tag_id' => $faker->randomDigit,
            //         ]);
            //     }
            // }

    }

protected $markdownBody = '---
__Advertisement :)__

- __[pica](https://nodeca.github.io/pica/demo/)__ - high quality and fast image
  resize in browser.
- __[babelfish](https://github.com/nodeca/babelfish/)__ - developer friendly
  i18n with plurals support and easy syntax.

You will like those projects!

---

# h1 Heading 8-)
## h2 Heading
### h3 Heading
#### h4 Heading
##### h5 Heading
###### h6 Heading


## Horizontal Rules

___

---

***


## Typographic replacements

Enable typographer option to see result.

(c) (C) (r) (R) (tm) (TM) (p) (P) +-

"Smartypants, double quotes" and


## Emphasis

**This is bold text**

__This is bold text__

*This is italic text*

_This is italic text_

~~Strikethrough~~


## Blockquotes


> Blockquotes can also be nested...
>> ...by using additional greater-than signs right next to each other...
> > > ...or with spaces between arrows.


## Lists

Unordered

+ Create a list by starting a line with `+`, `-`, or `*`
+ Sub-lists are made by indenting 2 spaces:
  - Marker character change forces new list start:
    * Ac tristique libero volutpat at
    + Facilisis in pretium nisl aliquet
    - Nulla volutpat aliquam velit
+ Very easy!

Ordered

1. Lorem ipsum dolor sit amet
2. Consectetur adipiscing elit
3. Integer molestie lorem at massa


1. You can use sequential numbers...
1. ...or keep all the numbers as `1.`

Start numbering with offset:

57. foo
1. bar


## Code

Inline `code`

Indented code

    // Some comments
    line 1 of code
    line 2 of code
    line 3 of code


Block code "fences"

```
Sample text here...
```

Syntax highlighting

``` js
var foo = function (bar) {
  return bar++;
};

console.log(foo(5));
```

## Tables

| Option | Description |
| ------ | ----------- |
| data   | path to data files to supply the data that will be passed into templates. |
| engine | engine to be used for processing templates. Handlebars is the default. |
| ext    | extension to be used for dest files. |

Right aligned columns

| Option | Description |
| ------:| -----------:|
| data   | path to data files to supply the data that will be passed into templates. |
| engine | engine to be used for processing templates. Handlebars is the default. |
| ext    | extension to be used for dest files. |


## Links

[link text](http://dev.nodeca.com)

[link with title](http://nodeca.github.io/pica/demo/ "title text!")

Autoconverted link https://github.com/nodeca/pica (enable linkify to see)


## Images

![Minion](https://octodex.github.com/images/minion.png)
![Stormtroopocat](https://octodex.github.com/images/stormtroopocat.jpg "The Stormtroopocat")

Like links, Images also have a footnote style syntax

![Alt text][id]

With a reference later in the document defining the URL location:

[id]: https://octodex.github.com/images/dojocat.jpg  "The Dojocat"


## Plugins

The killer feature of `markdown-it` is very effective support of
[syntax plugins](https://www.npmjs.org/browse/keyword/markdown-it-plugin).


### [Emojies](https://github.com/markdown-it/markdown-it-emoji)

> Classic markup: :wink: :crush: :cry: :tear: :laughing: :yum:
>
> Shortcuts (emoticons): :-) :-( 8-) ;)

see [how to change output](https://github.com/markdown-it/markdown-it-emoji#change-output) with twemoji.


### [Subscript](https://github.com/markdown-it/markdown-it-sub) / [Superscript](https://github.com/markdown-it/markdown-it-sup)

- 19^th^
- H~2~O

### [Footnotes](https://github.com/markdown-it/markdown-it-footnote)

Footnote 1 link[^first].

Footnote 2 link[^second].

Inline footnote^[Text of inline footnote] definition.

Duplicated footnote reference[^second].

[^first]: Footnote **can have markup**

    and multiple paragraphs.

[^second]: Footnote text.


### [Definition lists](https://github.com/markdown-it/markdown-it-deflist)

Term 1

:   Definition 1
with lazy continuation.

Term 2 with *inline markup*

:   Definition 2

        { some code, part of Definition 2 }

    Third paragraph of definition 2.

_Compact style:_

Term 1
  ~ Definition 1

Term 2
  ~ Definition 2a
  ~ Definition 2b


### [Abbreviations](https://github.com/markdown-it/markdown-it-abbr)

This is HTML abbreviation example.

It converts "HTML", but keep intact partial entries like "xxxHTMLyyy" and so on.

*[HTML]: Hyper Text Markup Language';



protected $firstPostBody =
'<hr>
<p><strong>Advertisement :)</strong></p>
<ul>
<li><strong><a href="https://nodeca.github.io/pica/demo/">pica</a></strong> - high quality and fast image
resize in browser.</li>
<li><strong><a href="https://github.com/nodeca/babelfish/">babelfish</a></strong> - developer friendly
i18n with plurals support and easy syntax.</li>
</ul>
<p>You will like those projects!</p>
<hr>
<h1>h1 Heading 8-)</h1>
<h2>h2 Heading</h2>
<h3>h3 Heading</h3>
<h4>h4 Heading</h4>
<h5>h5 Heading</h5>
<h6>h6 Heading</h6>
<h2>Horizontal Rules</h2>
<hr>
<hr>
<hr>
<h2>Typographic replacements</h2>
<p>Enable typographer option to see result.</p>
<p>© © ® ® ™ ™ § § ±</p>
<p>“Smartypants, double quotes” and</p>
<h2>Emphasis</h2>
<p><strong>This is bold text</strong></p>
<p><strong>This is bold text</strong></p>
<p><em>This is italic text</em></p>
<p><em>This is italic text</em></p>
<p><s>Strikethrough</s></p>
<h2>Blockquotes</h2>
<blockquote>
<p>Blockquotes can also be nested…</p>
<blockquote>
<p>…by using additional greater-than signs right next to each other…</p>
<blockquote>
<p>…or with spaces between arrows.</p>
</blockquote>
</blockquote>
</blockquote>
<h2>Lists</h2>
<p>Unordered</p>
<ul>
<li>Create a list by starting a line with <code>+</code>, <code>-</code>, or <code>*</code></li>
<li>Sub-lists are made by indenting 2 spaces:
<ul>
<li>Marker character change forces new list start:
<ul>
<li>Ac tristique libero volutpat at</li>
</ul>
<ul>
<li>Facilisis in pretium nisl aliquet</li>
</ul>
<ul>
<li>Nulla volutpat aliquam velit</li>
</ul>
</li>
</ul>
</li>
<li>Very easy!</li>
</ul>
<p>Ordered</p>
<ol>
<li>
<p>Lorem ipsum dolor sit amet</p>
</li>
<li>
<p>Consectetur adipiscing elit</p>
</li>
<li>
<p>Integer molestie lorem at massa</p>
</li>
<li>
<p>You can use sequential numbers…</p>
</li>
<li>
<p>…or keep all the numbers as <code>1.</code></p>
</li>
</ol>
<p>Start numbering with offset:</p>
<ol start="57">
<li>foo</li>
<li>bar</li>
</ol>
<h2>Code</h2>
<p>Inline <code>code</code></p>
<p>Indented code</p>
<pre><code>// Some comments
line 1 of code
line 2 of code
line 3 of code
</code></pre>
<p>Block code “fences”</p>
<pre><code>Sample text here...
</code></pre>
<p>Syntax highlighting</p>
<pre><code class="language-js">var foo = function (bar) {
  return bar++;
};

console.log(foo(5));
</code></pre>
<h2>Tables</h2>
<table>
<thead>
<tr>
<th>Option</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td>data</td>
<td>path to data files to supply the data that will be passed into templates.</td>
</tr>
<tr>
<td>engine</td>
<td>engine to be used for processing templates. Handlebars is the default.</td>
</tr>
<tr>
<td>ext</td>
<td>extension to be used for dest files.</td>
</tr>
</tbody>
</table>
<p>Right aligned columns</p>
<table>
<thead>
<tr>
<th style="text-align:right">Option</th>
<th style="text-align:right">Description</th>
</tr>
</thead>
<tbody>
<tr>
<td style="text-align:right">data</td>
<td style="text-align:right">path to data files to supply the data that will be passed into templates.</td>
</tr>
<tr>
<td style="text-align:right">engine</td>
<td style="text-align:right">engine to be used for processing templates. Handlebars is the default.</td>
</tr>
<tr>
<td style="text-align:right">ext</td>
<td style="text-align:right">extension to be used for dest files.</td>
</tr>
</tbody>
</table>
<h2>Links</h2>
<p><a href="http://dev.nodeca.com">link text</a></p>
<p><a href="http://nodeca.github.io/pica/demo/" title="title text!">link with title</a></p>
<p>Autoconverted link https://github.com/nodeca/pica (enable linkify to see)</p>
<h2>Images</h2>
<p><a href="https://octodex.github.com/images/minion.png" target="_blank"><img src="https://octodex.github.com/images/minion.png" alt="Minion" class="img-responsive"></a>
<a href="https://octodex.github.com/images/stormtroopocat.jpg" target="_blank"><img src="https://octodex.github.com/images/stormtroopocat.jpg" alt="Stormtroopocat" class="img-responsive"></a></p>
<p>Like links, Images also have a footnote style syntax</p>
<p><a href="https://octodex.github.com/images/dojocat.jpg" target="_blank"><img src="https://octodex.github.com/images/dojocat.jpg" alt="Alt text" class="img-responsive"></a></p>
<p>With a reference later in the document defining the URL location:</p>
<h2>Plugins</h2>
<p>The killer feature of <code>markdown-it</code> is very effective support of
<a href="https://www.npmjs.org/browse/keyword/markdown-it-plugin">syntax plugins</a>.</p>
<h3><a href="https://github.com/markdown-it/markdown-it-emoji">Emojies</a></h3>
<blockquote>
<p>Classic markup: :wink: :crush: :cry: :tear: :laughing: :yum:</p>
<p>Shortcuts (emoticons): :-) :-( 8-) ;)</p>
</blockquote>
<p>see <a href="https://github.com/markdown-it/markdown-it-emoji#change-output">how to change output</a> with twemoji.</p>
<h3><a href="https://github.com/markdown-it/markdown-it-sub">Subscript</a> / <a href="https://github.com/markdown-it/markdown-it-sup">Superscript</a></h3>
<ul>
<li>19<sup>th</sup></li>
<li>H<sub>2</sub>O</li>
</ul>
<h3><a href="https://github.com/markdown-it/markdown-it-footnote">Footnotes</a></h3>
<p>Footnote 1 link<sup class="footnote-ref"><a href="#fn1" id="fnref1">[1]</a></sup>.</p>
<p>Footnote 2 link<sup class="footnote-ref"><a href="#fn2" id="fnref2">[2]</a></sup>.</p>
<p>Inline footnote<sup class="footnote-ref"><a href="#fn3" id="fnref3">[3]</a></sup> definition.</p>
<p>Duplicated footnote reference<sup class="footnote-ref"><a href="#fn2" id="fnref2:1">[2:1]</a></sup>.</p>
<h3><a href="https://github.com/markdown-it/markdown-it-deflist">Definition lists</a></h3>
<dl>
<dt>Term 1</dt>
<dd>
<p>Definition 1
with lazy continuation.</p>
</dd>
<dt>Term 2 with <em>inline markup</em></dt>
<dd>
<p>Definition 2</p>
<pre><code>  { some code, part of Definition 2 }
</code></pre>
<p>Third paragraph of definition 2.</p>
</dd>
</dl>
<p><em>Compact style:</em></p>
<dl>
<dt>Term 1</dt>
<dd>Definition 1</dd>
<dt>Term 2</dt>
<dd>Definition 2a</dd>
<dd>Definition 2b</dd>
</dl>
<h3><a href="https://github.com/markdown-it/markdown-it-abbr">Abbreviations</a></h3>
<p>This is <abbr title="Hyper Text Markup Language">HTML</abbr> abbreviation example.</p>
<p>It converts “<abbr title="Hyper Text Markup Language">HTML</abbr>”, but keep intact partial entries like “xxxHTMLyyy” and so on.</p>
<hr class="footnotes-sep">
<section class="footnotes">
<ol class="footnotes-list">
<li id="fn1" class="footnote-item"><p>Footnote <strong>can have markup</strong></p>
<p>and multiple paragraphs. <a href="#fnref1" class="footnote-backref">↩︎</a></p>
</li>
<li id="fn2" class="footnote-item"><p>Footnote text. <a href="#fnref2" class="footnote-backref">↩︎</a> <a href="#fnref2:1" class="footnote-backref">↩︎</a></p>
</li>
<li id="fn3" class="footnote-item"><p>Text of inline footnote <a href="#fnref3" class="footnote-backref">↩︎</a></p>
</li>
</ol>
</section>';
}
