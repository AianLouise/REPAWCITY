export default function Pagination({
  page,
  lastPage,
  onChange,
}: {
  page: number
  lastPage: number
  onChange: (page: number) => void
}) {
  if (lastPage <= 1) return null

  const pages: (number | '...')[] = []
  for (let i = 1; i <= lastPage; i++) {
    if (i === 1 || i === lastPage || Math.abs(i - page) <= 1) {
      pages.push(i)
    } else if (pages[pages.length - 1] !== '...') {
      pages.push('...')
    }
  }

  const cls =
    'inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-medium transition-colors'

  return (
    <nav className="flex items-center justify-center gap-2 mt-10" aria-label="Pagination">
      <button
        onClick={() => onChange(page - 1)}
        disabled={page <= 1}
        className={`${cls} bg-white/70 border border-repaw-hover/40 text-repaw-text hover:bg-repaw-hover disabled:opacity-40 disabled:cursor-not-allowed`}
      >
        Prev
      </button>
      {pages.map((p, i) =>
        p === '...' ? (
          <span key={`e-${i}`} className="px-1 text-repaw-text/50">
            …
          </span>
        ) : (
          <button
            key={p}
            onClick={() => onChange(p)}
            className={`${cls} ${
              p === page
                ? 'bg-repaw-text text-repaw-bg'
                : 'bg-white/70 border border-repaw-hover/40 text-repaw-text hover:bg-repaw-hover'
            }`}
          >
            {p}
          </button>
        ),
      )}
      <button
        onClick={() => onChange(page + 1)}
        disabled={page >= lastPage}
        className={`${cls} bg-white/70 border border-repaw-hover/40 text-repaw-text hover:bg-repaw-hover disabled:opacity-40 disabled:cursor-not-allowed`}
      >
        Next
      </button>
    </nav>
  )
}
