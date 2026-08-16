export function PageHero({
  title,
  subtitle,
}: {
  title: string
  subtitle: string
}) {
  return (
    <section className="relative overflow-hidden">
      <div className="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none" />
      <div className="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
        <h1 className="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark">{title}</h1>
        <p className="mt-4 text-lg text-repaw-text/90 max-w-2xl mx-auto">{subtitle}</p>
      </div>
    </section>
  )
}

export function Loading() {
  return (
    <div className="flex items-center justify-center py-24">
      <div className="h-10 w-10 rounded-full border-4 border-repaw-hover border-t-repaw-text animate-spin" />
    </div>
  )
}

export function Empty({ message }: { message: string }) {
  return <p className="text-center text-repaw-text/80 text-lg py-12">{message}</p>
}
