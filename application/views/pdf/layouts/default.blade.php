<pdf>
    <page page-size="a4" document-template="{{ pdf_asset('layouts/default_background.pdf') }}">
        <placeholders>
            <header>
                <div height="100px"></div>
            </header>
            <footer>
                <div height="190px"></div>
            </footer>
        </placeholders>
        @yieldSection('content')
    </page>
</pdf>