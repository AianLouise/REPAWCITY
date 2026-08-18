import { formatDistanceToNow, parseISO } from 'date-fns'

export function timeAgo(dateString: string): string {
  const date = parseISO(dateString)
  return formatDistanceToNow(date, { addSuffix: true })
}
