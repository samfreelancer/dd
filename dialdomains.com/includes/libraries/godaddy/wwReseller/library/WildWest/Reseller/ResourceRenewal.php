<?php

class WildWest_Reseller_ResourceRenewal extends WildWest_Reseller_Element
{
    /**
     * The resource ID returned in a previous
     * notification message associated with the original order
     * for the domain name being renewed.
     * @var string
     */
    public $resourceid;

    /**
     * Maximum 63 characters. Second level domain name (abc of abc.com).
     * @var string
     */
    public $sld;

    /**
     * (.com, .net, .org, .us, .ws, or .info) Top level domain (com of abc.com)
     * @var string
     */
    public $tld;

    /**
     * Length of the registration, in years. Valid
     * values for most are 1-10.
     *
     * @var integer
     */
    public $period;

    /**
     * Associated Order Information
     * @var WildWest_Reseller_Order
     */
    public $order;
}
