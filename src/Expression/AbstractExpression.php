<?php

namespace RebelCode\Expression;

abstract class AbstractExpression extends AbstractTerm
{
    use TermsAwareTrait {
        _getTerms as public getTerms;
    }
}
