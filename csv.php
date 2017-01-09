<?php
$file_name = 'data/menu.csv';
$out_dir = "./content";

$file = new SplFileObject($file_name);
$file->setFlags(SplFileObject::READ_CSV);


$data = [];
$i = 0;
$keys = false;
$key_count = 0;

foreach ($file as $values) {
    if (empty($keys)) {
        // get key
        $keys = $values;
        $key_count = count($keys);
        continue;
    }

    if (count($values) != $key_count) {
        continue;
    }
    $data[] = array_combine($keys, $values);
}

// create md
foreach ($data as $key => $a) {
    $md_dir = "{$out_dir}/{$a['section']}";
    $md_file = "{$md_dir}/{$a['slug']}.md";
    @mkdir($md_dir);

    $tags = [];
    if (! empty($a['tags'])) {
        $tags = explode(',', $a['tags']);
    }
    $tags[] = (int)(($a['price']+99) / 100) . "00円以下";
    $tags = json_encode($tags, JSON_UNESCAPED_UNICODE);

    $data = <<< _END
---
weight: "{$key}"
title: {$a['title']}
tags: {$tags}
price: {$a['price']}
---

{$a['content']}
_END;

    file_put_contents($md_file, $data);
}
