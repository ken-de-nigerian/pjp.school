{{-- Shared styles for <x-site-logo> (admin, teacher, guest). Include once in layout <head>. --}}
<style>
    /* Site logo lockup — same typeface & letter-spacing on both lines; uppercase */
    .site-logo {
        display: inline-flex;
        flex-direction: row;
        align-items: center;
        gap: 0.625rem;
        min-width: 0;
        max-width: 100%;
        padding: 6px 10px;
        margin: 0;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: background-color 0.2s ease;
        box-sizing: border-box;
        /* Keep hover/focus background inside the rounded box (no paint past padding) */
        overflow: hidden;
    }

    .site-logo--app:hover {
        background: rgba(var(--primary-rgb), 0.08);
    }

    [data-theme="dark"] .site-logo--app:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .site-logo--guest:hover {
        background: rgba(15, 118, 110, 0.06);
    }

    .site-logo--footer:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .site-logo--footer .site-logo__mark {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.18);
        box-shadow: none;
    }

    .site-logo--footer .site-logo__line--primary {
        font-size: 0.625rem;
        color: #ffffff;
    }

    .site-logo--footer .site-logo__line--secondary {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.55);
    }

    @media (min-width: 640px) {
        .site-logo--footer .site-logo__line--primary {
            font-size: 0.6875rem;
        }

        .site-logo--footer .site-logo__line--secondary {
            font-size: 0.8125rem;
        }
    }

    @media (min-width: 1024px) {
        .site-logo--footer .site-logo__line--primary {
            font-size: 0.75rem;
        }

        .site-logo--footer .site-logo__line--secondary {
            font-size: 0.875rem;
        }
    }

    .site-logo__mark {
        position: relative;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 10px;
        overflow: hidden;
        background: var(--surface-container, #ece6f0);
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
    }

    .site-logo--guest .site-logo__mark {
        background: #f0fdfa;
        border-color: rgba(15, 118, 110, 0.15);
    }

    .site-logo__img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .site-logo__wordmark {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        gap: 0.15rem;
        min-width: 0;
        flex: 1 1 auto;
        max-width: min(11rem, 100%);
        overflow: hidden;
        line-height: 1.2;
    }

    .site-logo__line {
        font-family: var(--font-sans);
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        box-sizing: border-box;
        /* Letter-spacing draws past the last glyph; pad so text stays inside hover bg */
        padding-inline-end: 0.14em;
    }

    /* Primary = longer school name: keep it smaller than secondary to reduce overflow */
    .site-logo--app .site-logo__line--primary {
        font-size: 0.5625rem;
        color: var(--on-surface, #1c1b1f);
    }

    .site-logo--app .site-logo__line--secondary {
        font-size: 0.6875rem;
        color: var(--on-surface-variant, #49454f);
    }

    @media (min-width: 640px) {
        .site-logo {
            gap: 0.75rem;
        }

        .site-logo__mark {
            width: 2.875rem;
            height: 2.875rem;
        }

        .site-logo__wordmark {
            max-width: min(14rem, 100%);
        }

        .site-logo--app .site-logo__line--primary {
            font-size: 0.625rem;
        }

        .site-logo--app .site-logo__line--secondary {
            font-size: 0.75rem;
        }
    }

    @media (min-width: 1024px) {
        .site-logo__mark {
            width: 3rem;
            height: 3rem;
            border-radius: 11px;
        }

        .site-logo__wordmark {
            max-width: min(18rem, 100%);
        }

        .site-logo--app .site-logo__line--primary {
            font-size: 0.6875rem;
        }

        .site-logo--app .site-logo__line--secondary {
            font-size: 0.8125rem;
        }
    }

    [data-theme="dark"] .site-logo__mark {
        border-color: rgba(255, 255, 255, 0.1);
        background: var(--surface-container-high, #2b2930);
    }

    /* Guest layout: inherit body font (e.g. DM Sans), same metrics on both lines */
    .site-logo--guest .site-logo__line {
        font-family: inherit;
    }

    .site-logo--guest .site-logo__line--primary {
        font-size: 0.625rem;
        color: var(--on-surface, #1c1b1f);
    }

    .site-logo--guest .site-logo__line--secondary {
        font-size: 0.75rem;
        color: var(--on-surface-variant, #49454f);
    }

    @media (min-width: 640px) {
        .site-logo--guest .site-logo__line--primary {
            font-size: 0.6875rem;
        }

        .site-logo--guest .site-logo__line--secondary {
            font-size: 0.8125rem;
        }
    }

    @media (min-width: 1024px) {
        .site-logo--guest .site-logo__line--primary {
            font-size: 0.75rem;
        }

        .site-logo--guest .site-logo__line--secondary {
            font-size: 0.875rem;
        }
    }
</style>
