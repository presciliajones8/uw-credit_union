<?php

/**
 * Returns the configured public BTC receiving address, or null when deposits
 * have not been configured. Set BTC_RECEIVING_ADDRESS in the server
 * environment; do not place a wallet seed, private key, or test address here.
 */
function configured_btc_receiving_address(): ?string
{
    $address = trim((string) getenv('BTC_RECEIVING_ADDRESS'));

    if ($address === '') {
        return null;
    }

    $isLegacyOrScriptAddress = preg_match('/^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$/', $address) === 1;
    $isBech32Address = preg_match('/^bc1[ac-hj-np-z02-9]{11,71}$/', strtolower($address)) === 1;

    return ($isLegacyOrScriptAddress || $isBech32Address) ? $address : null;
}
