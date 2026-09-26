<?php

namespace Matasar\Euspe\Exception;

/**
 * Extends DecryptionException, which 1.x threw for the same failure, so existing catch blocks keep working.
 */
class VerificationException extends DecryptionException
{
}
