<pdf>
    <page page-size="a4" document-template="{{ pdf_asset('layouts/testarrangement/default_background.pdf') }}">
        <placeholders>
            <header>
                <div class="header" height="120px">@yieldSection('header')</div>
            </header>
            <footer>
                <div height="190px">@yieldSection('footer')</div>
            </footer>
        </placeholders>
        @yieldSection('content')
    </page>
</pdf>