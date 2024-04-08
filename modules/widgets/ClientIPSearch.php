<?php

/**
 * Standard add_hook call @see https://developers.whmcs.com/hooks/getting-started/
 */
add_hook('AdminHomeWidgets', 1, function() {
    /**
     * Return a new instance of the widget object for display
     */
    return new ClientIPSearch();
});

/**
 * Sample Widget example
 */
class ClientIPSearch extends \WHMCS\Module\AbstractWidget
{
    /**
     * @type string The title of the widget
     */
    protected $title = 'Client IP Search';

    /**
     * @type string A description/purpose of the widget
     */
    protected $description = '';

    /**
     * @type int The sort weighting that determines the output position on the page
     */
    protected $weight = 150;

    /**
     * @type int The number of columns the widget should span (1, 2 or 3)
     */
    protected $columns = 1;

    /**
     * @type bool Set true to enable data caching
     */
    protected $cache = false;

    /**
     * @type int The length of time to cache data for (in seconds)
     */
    protected $cacheExpiry = 120;

    /**
     * @type string The access control permission required to view this widget. Leave blank for no permission.
     * @see Permissions section below.
     */
    protected $requiredPermission = '';

    /**
     * Get Data.
     *
     * Obtain data required to render the widget.
     *
     * We recommend executing queries and API calls within this function to enable
     * you to take advantage of the built-in caching functionality for improved performance.
     *
     * When caching is enabled, this method will be called when the cache is due for
     * a refresh or when the user invokes it.
     *
     * @return array
     */
    public function getData()
    {
        $clients = localAPI('getclients', []);

        return array(
            'welcome' => 'Hello World!',
            'clients' => $clients['clients'],
        );
    }

    /**
     * Generate Output.
     *
     * Generate and return the body output for the widget.
     *
     * @param array $data The data returned by the getData method.
     *
     * @return string
     */
    public function generateOutput($data)
    {
        $clientOutput = [];
        foreach ($data['clients']['client'] as $client) {
            $clientOutput[] = "<a href=\"clientsprofile.php?id={$client['id']}\">{$client['firstname']} {$client['lastname']}</a>";
        }

        if (count($clientOutput) == 0) {
            $clientOutput[] = 'No Clients Found';
        }

        $clientOutput = implode('<br>', $clientOutput);

        return <<<EOF
<form method="get" action="addonmodules.php">
{$GLOBALS['CONFIG']['Token']}
<input type="hidden" name="module" value="clientipsearch">
    <div align="center" style="margin:10px 40px;font-size:16px;">
        <div class="input-group">
            <input type="text" name="ip" placeholder="IP Address" value="" class="form-control">
            <span class="input-group-btn">
                <input type="submit" class="btn btn-primary">
            </span>
        </div>
    </div>
</form>

EOF;
    }
}