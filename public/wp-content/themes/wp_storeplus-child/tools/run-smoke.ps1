# Smoke wrapper for Windows (Local PHP CLI often lacks curl/mysqli).
# Usage: powershell -File tools/run-smoke.ps1 [-Base http://rastadarou.local]
param(
	[string]$Base = "http://rastadarou.local"
)

$ErrorActionPreference = "Stop"
$Base = $Base.TrimEnd("/")

function Get-Page([string]$Url) {
	$sw = [System.Diagnostics.Stopwatch]::StartNew()
	try {
		$r = Invoke-WebRequest -Uri $Url -UseBasicParsing -TimeoutSec 30 -MaximumRedirection 5
		$sw.Stop()
		$final = $Url
		if ($r.BaseResponse -and $r.BaseResponse.ResponseUri) {
			$final = [string]$r.BaseResponse.ResponseUri.AbsoluteUri
		}
		return @{
			Ok     = ($r.StatusCode -ge 200 -and $r.StatusCode -lt 400)
			Code   = [int]$r.StatusCode
			Final  = $final
			Body   = [string]$r.Content
			Detail = "HTTP $($r.StatusCode) · $($sw.ElapsedMilliseconds)ms · $final"
		}
	} catch {
		$sw.Stop()
		return @{ Ok = $false; Code = 0; Final = $Url; Body = ""; Detail = $_.Exception.Message }
	}
}

function Get-MissingMarkers([string]$Body, [hashtable]$Map) {
	$missing = New-Object System.Collections.Generic.List[string]
	foreach ($k in $Map.Keys) {
		if ($Body.IndexOf($k) -lt 0) { [void]$missing.Add($Map[$k]) }
	}
	return $missing
}

Write-Host "=== StorePlus smoke (PowerShell) ==="
Write-Host "base: $Base"

$fail = 0

# HOME (avoid $HOME — reserved in PowerShell)
$homeRes = Get-Page "$Base/"
$miss = Get-MissingMarkers $homeRes.Body @{
	"irantheme-footer-nav-section" = "footer"
	"data-sp-php-footer"           = "php-footer"
	"sp-php-home-order"            = "home-order"
}
$ok = $homeRes.Ok -and ($miss.Count -eq 0)
if (-not $ok) { $fail++ }
$homeDetail = if ($miss.Count) { "missing: $($miss -join ',')" } else { $homeRes.Detail }
$homeMark = if ($ok) { "PASS" } else { "FAIL" }
Write-Host "[$homeMark] home — $homeDetail"

# PDP
$prod = $null
$m = [regex]::Match($homeRes.Body, 'href="(https?://[^"]+/product/[^"?#]+/?)"')
if ($m.Success) {
	$prod = [System.Net.WebUtility]::HtmlDecode($m.Groups[1].Value)
}
if (-not $prod) {
	$shopRes = Get-Page "$Base/shop/"
	$m2 = [regex]::Match($shopRes.Body, 'href="(https?://[^"]+/product/[^"?#]+/?)"')
	if ($m2.Success) {
		$prod = [System.Net.WebUtility]::HtmlDecode($m2.Groups[1].Value)
	}
}
if (-not $prod) {
	Write-Host "[FAIL] pdp — no product URL on homepage/shop"
	$fail++
} else {
	$pdpRes = Get-Page $prod
	$miss = Get-MissingMarkers $pdpRes.Body @{
		"woocommerce"        = "wc"
		"product"            = "product"
		"data-sp-php-footer" = "php-footer"
	}
	$ok = $pdpRes.Ok -and ($miss.Count -eq 0)
	if (-not $ok) { $fail++ }
	$pdpDetail = if ($miss.Count) { "missing: $($miss -join ',')" } else { $pdpRes.Detail }
	$pdpMark = if ($ok) { "PASS" } else { "FAIL" }
	Write-Host "[$pdpMark] pdp — $pdpDetail"
}

# CART
$cartRes = Get-Page "$Base/cart/"
$miss = Get-MissingMarkers $cartRes.Body @{
	"woocommerce"        = "wc"
	"data-sp-php-footer" = "php-footer"
}
$ok = $cartRes.Ok -and ($miss.Count -eq 0)
if (-not $ok) { $fail++ }
$cartDetail = if ($miss.Count) { "missing: $($miss -join ',')" } else { $cartRes.Detail }
$cartMark = if ($ok) { "PASS" } else { "FAIL" }
Write-Host "[$cartMark] cart — $cartDetail"

# CHECKOUT (guest auth gate is success for this store)
$chkRes = Get-Page "$Base/checkout/"
$fin = [string]$chkRes.Final
$auth = ($fin -match "my-account|login|sp_need_auth") -or ($chkRes.Body -match "sp_need_auth")
$isChk = ($fin -match "checkout")
$ok = $chkRes.Ok -and ($auth -or $isChk)
if (-not $ok) { $fail++ }
$chkDetail = if ($auth) { "auth gate · $($chkRes.Detail)" } else { $chkRes.Detail }
$chkMark = if ($ok) { "PASS" } else { "FAIL" }
Write-Host "[$chkMark] checkout — $chkDetail"

Write-Host ""
$result = if ($fail -eq 0) { "PASS" } else { "FAIL" }
Write-Host "RESULT: $result (failures=$fail)"
exit $fail
