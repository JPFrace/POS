// composables/reports/useExcelExport.ts
import * as XLSX from "xlsx";

export const useExcelExport = () => {
    const { $message } = useNuxtApp();

    /**
     * Export report to Excel using the modern xlsx library
     */
    const exportToExcel = () => {
        if (!import.meta.client) return;

        const reportContainer = document.querySelector(
            ".preview-report"
        ) as HTMLElement;

        if (!reportContainer) {
            $message("error", "No report found to export!");
            return;
        }

        try {
            // Clone and clean the DOM
            const clone = reportContainer.cloneNode(true) as HTMLElement;

            // Remove unwanted elements
            clone
                .querySelectorAll(".el-popper, .el-dropdown-menu, button")
                .forEach((el) => el.remove());

            // Get all tables or use the entire clone
            const tables = clone.querySelectorAll("table");
            const workbook = XLSX.utils.book_new();

            if (tables.length > 0) {
                // Export each table as a separate sheet
                tables.forEach((table, index) => {
                    const worksheet = XLSX.utils.table_to_sheet(table);
                    const sheetName =
                        tables.length > 1 ? `Sheet${index + 1}` : "Report";
                    XLSX.utils.book_append_sheet(
                        workbook,
                        worksheet,
                        sheetName
                    );
                });
            } else {
                // If no tables, convert the entire content
                const worksheet = XLSX.utils.table_to_sheet(clone);
                XLSX.utils.book_append_sheet(workbook, worksheet, "Report");
            }

            // Generate filename
            const filename = generateFilename();

            // Export to Excel file
            XLSX.writeFile(workbook, filename);

            $message("success", "Report exported to Excel successfully!");
        } catch (error) {
            console.error("Excel export failed:", error);
            $message("error", "Failed to export report to Excel");
        }
    };

    /**
     * Generate filename based on current report
     */
    const generateFilename = (): string => {
        const today = new Date().toISOString().slice(0, 10);
        const reportData = useState<{ name?: string }>("bookmarkData");

        const reportName = reportData.value?.name || "Report";

        const sanitized = reportName
            .replace(/\s+/g, "_")
            .replace(/[&]/g, "and")
            .replace(/[^a-z0-9_]/gi, "")
            .replace(/_+/g, "_");

        return `${sanitized}_${today}.xlsx`;
    };

    return {
        exportToExcel,
    };
};
