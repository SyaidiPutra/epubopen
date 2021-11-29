<?php

include('../epubOpen/EpubRead.php');

$epub = new EpubRead();
$epub->read("../sampel.epub");