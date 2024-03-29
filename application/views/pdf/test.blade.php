@layout('pdf.layouts.default')
@section('content')   
        <h1>Disclaimer</h1>
        <p>The information in this document is subject to change without notice and does not represent a commitment on the part of Zend Technologies Ltd. No part of this manual may be reproduced or transmitted in any form or by any means, electronic or mechanical, including photocopying, recording, or information storage and retrieval systems, for any purpose other than the purchaser’s personal use, without the written permission of Zend Technologies Ltd.</p>
        <p>All trademarks mentioned in this document, belong to their respective owners.<br />
        © 1999-2010 Zend Technologies Ltd. All rights reserved.</p>
        <p>Zend Studio 8.0 User Guide issued October 2010.</p>
        <p>Product Version: 8.x<br />
        DN: ZS-UG-281010-08-19</p>
                
        <break />
        
        <h1>What's New in Zend Studio 8.x</h1>
        <h2>The following new features are available in Zend Studio 8.0:</h2>
        <ul>
            <li><a>Ajax Tools</a></li>
            <li><a>The Internal Web Browser</a></li>
            <li><a>JavaScript Libraries</a></li>
            <li><a>Debugging JavaScript</a></li>
            <li><a>Remote Server Support</a></li>
            <li><a>VMware Workstation Integration</a></li>
        </ul>
        
        <div class="margin"></div>
        
        <div class="note">
            <div class="header">Note:</div>
            <div class="content">To see What's New in previous versions of Zend Studio go to the <a href="http://www.zend.com/en/products/studio/studio-whats-new">Zend Studio What's New</a> section of the Zend website.</div>
        </div>
        
        <break />
        
        <h1>Mac OS X</h1>
        
        <p>If you are running your Zend Studio on Mac OS X be aware that some Menu items may be labeled differently. This difference in labeling does not change the procedure. For a list of the alternate labels, see the table below.</p>
        
        <table width="75%">
            <tr class="header">
                <td>Windows</td>
                <td>Mac OS X</td>
            </tr>
            <tr>
                <td>Help | About Zend Studio</td>
                <td>Zend Studio | About Zend Studio</td>
            </tr>
            <tr>
                <td>Window | Preferences</td>
                <td>Zend Studio | Preferences</td>
            </tr>
        </table>
        
        <break />
        
        <h2>Debbuging</h2>
        
        <div class="note">
            <div class="header">Note:</div>
            <div class="content">By creating a debug launch configuration in Zend Studio 8.x, you can easily rerun the debug session with the settings specified by clicking the arrow next to the debug button on the toolbar and selecting your launch configuration.</div>
        </div>
        
        <div class="margin"></div>
        
        <strong>To debug a PHP script using Zend Studio's internal debugger:</strong>
        
        <table width="100%">
            <tr class="header">
                <td>Zend Studio 5.x</td>
                <td>Zend Studio 8.x</td>
            </tr>
            <tr>
                <td>
                    <ul type="decimal">
                        <li>Open the Preferences window by selecting <strong>Tools | Preferences</strong> from the main menu.</li>
                        <li>Select the Debug tab.</li>
                        <li>From the Debug Server Configuration area of the Debug tab, select 'internal'' from the Debug Mode category.</li>
                        <li>Click <strong>Apply</strong> and <strong>OK</strong>.</li>
                        <li>In the main toolbar, click Go to start the Debugger. <br />-or- from the Menu Bar select <strong>Debug | Go</strong>.</li>
                    </ul>
                </td>
                <td>
                    <ul type="decimal">
                        <li>Click the arrow next to the debug button on the toolbar and select <strong>Debug As | PHP Script</strong>. <br />-Or- In PHP Explorer view, right-click the required file and select <strong>Debug As | PHP Script</strong>.</li>
                    </ul>
                </td>
            </tr>
        </table>
        See <a ref="locally-debuging">Locally Debugging a PHP Script</a> for more information.
        
        <break />
        
        <table width="100%">
            <tr class="header">
                <td width="40%">Zend Studio 5.x</td>
                <td width="60%">Zend Studio 8.x</td>
            </tr>
            <tr>
                <td>
                    <ul type="decimal">
                        <li>Open the Preferences window by selecting <strong>Tools | Preferences</strong> from the main menu.</li>
                        <li>Select the Debug tab.</li>
                        <li>From the Debug Server Configuration area of the Debug tab, select 'server' from the Debug Mode category.</li>
                        <li>Enter the URL of the server on which you want to Debug your files.</li>
                        <li>Click <strong>Apply</strong> and <strong>OK</strong>.</li>
                        <li>In Zend Studio’s main toolbar, click Run to start the Debugger.</li>
                    </ul>
                </td>
                <td>
                    <ul type="decimal">
                        <li>
                            Click the arrow next to the debug button on the toolbar and select Debug Configurations... -or- In PHP Explorer view, right-click and select <strong>Debug as | Debug Configurations...</strong><br />
                            A Debug dialog will open.
                        </li>
                        <li>Double-click the PHP Script option to create a new debug configuration and enter a name for it.</li>
                        <li>Select the PHP Web Server option under the Debugger Location category and select your server from the list.</li>
                        <li>Under PHP File, click <strong>Browse</strong> and select your file.</li>
                        <li>Click <strong>Apply</strong> and <strong>Debug</strong>.</li>
                    </ul>
                    <div class="note">
                        <div class="header">Note:</div>
                        <div class="content">The next time you want to run this debug session, click the arrow next to the debug button on the toolbar and select your launch configuration.</div>
                    </div>
                </td>
            </tr>
        </table>
        See <a>Remotely Debugging a PHP Script</a> for more information.
        
        <break />
        
        <h1 id="locally-debuging">Locally Debugging a PHP Script</h1>
        <p>This procedure describes how to debug a PHP Script from your workspace using an internal <a>PHP Executable</a>.</p>
        
        <div float="left" width="10%">
            
        </div>
        <div float="left" width="90%">
            <strong>To locally debug a PHP Script:</strong>
            <ul type="decimal" margin-left="0">
                <li>Set breakpoints at the relevant places in the file that you would like to debug by double-clicking the vertical marker bar to the left of the editor.</li>
                <li>Save the file.</li>
                <li>Click the arrow next to the debug button on the toolbar and select Debug Configurations... -or- select <strong>Run | Debug Configurations....</strong><nl/>A Debug dialog will open.</li>
                <li>
                    Double-click the PHP Script option to create a new debug configuration.
                    
                </li>
                <li>Enter a name for the new configuration.</li>
                <li>Ensure that the "PHP Executable" option is selected under the Debugger Location category and s elect the required PHP executable.</li>
                <li>Select the Zend Debugger from the PHP Debbuger list.</li>
                <li>Enter your PHP file in the "PHP File" text field, or click <strong>Browse</strong> and select your file</li>
                <li>Marking the "Breakpoint" checkbox will result in the debugging process pausing at the first line of code.</li>
                <li>If necessary, you can add arguments in the PHP Script Arguments tab to simulate command line inputs.</li>
                <li>Click <strong>Apply</strong> and then <strong>Debug</strong>.</li>
                <li>Click <strong>Yes</strong> if asked whether to open the PHP Debug Perspective.</li>
            </ul>
        </div>
        <p>
            A number of views will open with relevant debug information.<br />
            See the Running and Analyzing Debugger results topic for more information on the outcome of a debugging process.
        </p>
        
        <div class="note">
            <div class="header">Note:</div>
            <div class="content">
                If the file contains 'include' or 'require' calls to files which are not contained within the project, you must <a>add them to the project's Include Path</a> in order to simulate your production environment.
            </div>
        </div>
        
        <break />

        <h1>Creating a PHPDoc</h1>
        <p>This procedure describes how to create a PHPDoc from your PHP files.</p>
        <div float="left" width="10%">
            
        </div>
        <div float="left" width="90%">
            <strong>To create a PHPDoc:</strong>
            <ul type="decimal" margin-left="0">
                <li>
                    Right-click the project from which you would like the PHPDoc to be generated and select Generate PHP Doc -or- go to Project | Generate PHPDoc -or- press Alt+D. The PHPDoc Generation dialog will open.
                    <div class="image">
                        
                        
                        <p>PHPDoc Generation dialog 1</p>
                    </div>
                </li>
                <li>If you have previously created PHPDoc settings which you would like to apply, mark the checkbox. To create a settings configuration, see <a ref="point-8">point 8</a>.</li>
                <li>Ensure that the required project, destination for the PHPDoc and PHP Executable are selected.</li>
                <li>
                    Click Next.
                    <div class="image">
                        
                        
                        <p>PHPDoc Generation dialog 2</p>
                    </div>
                </li>
                <li>Choose the style for your PHPDoc from the 'Converter Type' drop-down list. This will affect the layout and format of your PHPDoc.</li>
                <li>
                    Select which basic options you want to apply: (Refer to the phpDoc Manual online at <a href="http://www.phpdoc.org">http://www.phpdoc.org</a> for complete descriptions of the options.)
                    <ul>
                        <li>Parse @acess private and @internal</li>
                        <li>Generate highlighted source code</li>
                        <li>JavaDoc-compliant description parsing</li>
                        <li>PEAR package repository parsing</li>
                        <li>Descend into hidden directories</li>
                    </ul>
                </li>
                <li>
                    Enter the following fields: (Refer to the phpDoc Manual online at <a href="http://www.phpdoc.org">http://www.phpdoc.org</a> for complete descriptions of the options.)
                    <ul>
                        <li>Default package name</li>
                        <li>Default category name</li>
                        <li>Custom tags (if required)</li>
                        <li>Ignore tags (if required)</li>
                        <li>Examples directories path (if required)</li>
                    </ul>
                </li>
                <li id="point-8">To save these settings so that they can be reused when creating new PHPDocs, mark the 'Save the settings of this PHPDoc export in an ini file" checkbox and specify where the ini file should be saved.</li>
                <li>Ensure the 'Open generated documentation in browser checkbox is marked to view your PHPDoc once created.</li>
                <li>Click Finish.<nl/>A Running PHPDocumentor dialog will appear.</li>
            </ul>
        </div>
        
        <p>Your PHPDoc will be automatically created and will be opened in a browser.
By default, your phpdoc is created as an index.html file in a folder entitled 'docs' in the root of you Workspace. (e.g. C:\Documents and Settings\bob\Zend\workspaces\DefaultWorkspace\docs\index.html).</p>

        <break />

        <h1>Index</h1>
        
        <column-layout equals-columns="true">
            <p><strong>A</strong></p>
            <p>Absolute...................................................142</p>
            <p>Access Rules ...........................................525</p>
            <p>action .......................................................315</p>
            <p>Activating Tunneling ................................348</p>
            <p>add.......................... 441, 465, 507, 508, 510</p>
            <p>Add ..................................................514, 520</p>
            <p>add comment ...........................................510</p>
            <p>Add CVS Repository..................................40</p>
            <p>add JavaScript library ..............507, 508, 514</p>
            <p>add JavaScript library folder ....................520</p>
            <p>add jQuery JavaScript library ..................507</p>
            <p>add JSDoc comment ...............................510</p>
            <p>add library ................................160, 508, 512</p>
            <p>add library folder ......................................520</p>
            <p>Adding an SVN Repository........................48</p>
            <p>Ajax. 176, 533, 539, 545, 552, 555, 637, 748</p>
            <p>Ajax DOM inspector view ........................539</p>
            <p>ajax tool ...................................................637</p>
            <p>ajax tools................. 176, 533, 552, 555, 748</p>
            <p>Allowed Host............................................336</p>
            <p>amf...........................................................200</p>
            <p>amf file .................................................... 200</p>
            <p>Analyzer .................................................. 124</p>
            <p>analyzing JavaScript code .............. 160, 512</p>
            <p>Appearance preferences ........................ 675</p>
            <p>attribute DOM.......................................... 539</p>
            <p>Auto Detection Port......................... 221, 345</p>
            <p>Average Own Time ................................... 68</p>
            <p><strong>B</strong></p>
            <p>Bookmarks .............................................. 115</p>
            <p>Bookmarks view...................................... 115</p>
            <p>bottlenecks................................................ 68</p>
            <p>Boundry Maker........................................ 104</p>
            <p>box model ............................................... 555</p>
            <p>box model tab ......................................... 555</p>
            <p>bracket .................................................... 109</p>
            <p>brackets .................................................. 107</p>
            <p>Breakpoint....................................... 141, 629</p>
            <p>breakpoints ............................... 55, 133, 605</p>
            <p>Breakpoints view............................. 605, 629</p>
            <p>browser ........................................... 537, 545</p>
            <p>browser console...................................... 545</p>
            <p>Browser Console View............................ 545</p>
            <p>browser internal ...................... 176, 533, 750</p>
            <p>Browser Output view............................... 611</p>
            <p>browser preferences ............................... 750</p>
            <p>Browser Toolbar...................................... 147</p>
            <p>browser web............................................ 750</p>
            <p>Build Path................................................ 143</p>
            <p>Build Path JavaScript.............................. 478</p>
            <p><strong>C</strong></p>
            <p>Called Parameters .................................. 607</p>
            <p>Calls Count ............................................... 68</p>
            <p>Check Out................................................255</p>
            <p>Checking Out Projects.........................40, 48</p>
            <p>class.........................................................508</p>
            <p>Class Type Hints........................................35</p>
            <p>classes.....................................................507</p>
            <p>Code Analyzer .........................................124</p>
            <p>Code Assist..................................35, 96, 222</p>
            <p>Code Assist preferences .........................705</p>
            <p>Code Coverage Preferences ...................677</p>
            <p>Code Coverage Summary .........................68</p>
            <p>code elements .........................................222</p>
            <p>Code Folding ...................................112, 228</p>
            <p>Code Gallery........... 163, 415, 416, 417, 419</p>
            <p>Code Gallery preferences........................679</p>
            <p>code JavaScript .......................................510</p>
            <p>Code snippet........... 163, 415, 416, 417, 419</p>
            <p>code trace ................................619, 620, 739</p>
            <p>code tracer perspective ...........................619</p>
            <p>code tracing ............ 153, 199, 619, 620, 739</p>
            <p>code tracing integrate ..............................153</p>
            <p>code tracing Zend Server ........................153</p>
            <p>colors .......................................................104</p>
            <p>comment ..................................................245</p>
            <p>comment add ...........................................510</p>
            <p>comment JSDoc ......................................510</p>
            <p>Commenting ............................................113</p>
            <p>Commenting Code...................................113</p>
            <p>Comments ...............................................113</p>
            <p>communication port .................................148</p>
            <p>Communication Settings..........................148</p>
            <p>communication tunnel......................221, 345</p>
            <p>compare...................................................249</p>
            <p>compare node..........................................539</p>
            <p>computed styles.......................................555</p>
            <p>Concurrent Versions System.............40, 126</p>
            <p>Conditional Breakpoints...........................141</p>
            <p>Configure Include Paths ..........................371</p>
            <p>Connecting to a Database ...................... 299</p>
            <p>Connection Profile........... 297, 439, 441, 446</p>
            <p>console browser...................................... 545</p>
            <p>Content Assist 160, 507, 508, 510, 512, 514, 520</p>
            <p>Content Assist JavaScript....................... 478</p>
            <p>Controller ........................................ 128, 269</p>
            <p>Cookie............................................. 221, 345</p>
            <p>Covered Lines........................................... 68</p>
            <p>create .............................................. 448, 572</p>
            <p>css........................................................... 555</p>
            <p>css properties.......................................... 555</p>
            <p>css rules.................................................. 555</p>
            <p>css value ................................................. 555</p>
            <p>Current Working Directory ...................... 142</p>
            <p>custom machine...................................... 572</p>
            <p>custom virtual machine ........................... 572</p>
            <p>CVS................................... 40, 126, 252, 253</p>
            <p>CVS connection ...................... 252, 255, 259</p>
            <p>CVS perspective ..................................... 253</p>
            <p>CVS Repository ................ 40, 253, 255, 259</p>
            <p>CVS Repository Exploring ...................... 253</p>
            <p>CVS Repository Exploring Perspective . 126, 255</p>
        </column-layout>
@endsection