<?php
// Get template part of header head

use Irantheme_Dev\Irantheme_Header_Main;
use Irantheme_Dev\Irantheme_Page_Builder;

Irantheme_Header_Main::get_header(Irantheme_Page_Builder::get_current_page_id());
