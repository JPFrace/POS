import { computed, watch } from 'vue'
import { useHead, useRoute } from '#imports'

function isUUID(segment: string): boolean {
  return /^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i.test(segment)
}

function formatSegment(segment: string): string {
  return segment.replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase())
}

export function usePageTitle() {
  const route = useRoute()

  const title = computed(() => {
    const segments = route.path.split('/').filter(Boolean)

    if (segments.length === 0) return 'Home'

    const last = segments[segments.length - 1]
    const secondLast = segments[segments.length - 2]
    const first = segments[0]

    // Case: UUID → Edit - Page
    if (isUUID(last) && secondLast) {
      return `Edit - ${formatSegment(secondLast)}`
    }

    // Case: Single segment → Just the name
    if (segments.length === 1) {
      return formatSegment(segments[0])
    }

    // Case: Multiple segments → Category - Page
    const category = formatSegment(first)
    const page = formatSegment(last)
    return category === page ? category : `${category} - ${page}`
  })

  useHead({ title: title.value })

  watch(title, (newTitle) => {
    useHead({ title: newTitle })
  })
}