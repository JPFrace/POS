import type { Reports } from '~/types/reports'

export interface ReportSignatories {
    label: string | null
    signatory: string | null
    report: Reports | null
    is_inactive: boolean
    sort: number | string | null
    year_signatory: number | null
}
