/* eslint-disable @typescript-eslint/no-explicit-any */

const STORAGE_PREFIX = 'arya-tv';

type StoredChart = {
    id: string;
    name: string;
    symbol: string;
    resolution: string;
    content: string;
    timestamp: number;
};

type StoredStudyTemplate = {
    name: string;
    content: string;
};

type StoredDrawingTemplate = {
    toolName: string;
    name: string;
    content: string;
};

const readJson = <T>(key: string, fallback: T): T => {
    if (typeof window === 'undefined') return fallback;

    try {
        const raw = localStorage.getItem(key);
        return raw ? (JSON.parse(raw) as T) : fallback;
    } catch {
        return fallback;
    }
};

const writeJson = (key: string, value: unknown) => {
    if (typeof window === 'undefined') return;

    try {
        localStorage.setItem(key, JSON.stringify(value));
    } catch (error) {
        console.error('[TradingViewLocalStorageAdapter] failed to write', error);
    }
};

const chartsKey = `${STORAGE_PREFIX}-charts`;
const studyTemplatesKey = `${STORAGE_PREFIX}-study-templates`;
const drawingTemplatesKey = `${STORAGE_PREFIX}-drawing-templates`;
const chartTemplatesKey = `${STORAGE_PREFIX}-chart-templates`;
const lineToolsKey = `${STORAGE_PREFIX}-line-tools`;

export const createTradingViewLocalStorageAdapter = () => ({
    getAllCharts(): Promise<any[]> {
        const charts = readJson<StoredChart[]>(chartsKey, []);
        return Promise.resolve(
            charts.map(({ id, name, symbol, resolution, timestamp }) => ({
                id,
                name,
                symbol,
                resolution,
                timestamp,
            })),
        );
    },

    removeChart(id: string | number): Promise<void> {
        const charts = readJson<StoredChart[]>(chartsKey, []);
        const nextCharts = charts.filter((chart) => String(chart.id) !== String(id));
        writeJson(chartsKey, nextCharts);
        return Promise.resolve();
    },

    saveChart(chartData: any): Promise<string> {
        const charts = readJson<StoredChart[]>(chartsKey, []);
        const id = chartData.id ? String(chartData.id) : `${Date.now()}`;
        const nextChart: StoredChart = {
            id,
            name: chartData.name,
            symbol: chartData.symbol,
            resolution: chartData.resolution,
            content: chartData.content,
            timestamp: Date.now(),
        };

        const index = charts.findIndex((chart) => chart.id === id);
        if (index >= 0) {
            charts[index] = nextChart;
        } else {
            charts.push(nextChart);
        }

        writeJson(chartsKey, charts);
        return Promise.resolve(id);
    },

    getChartContent(chartId: string | number): Promise<string> {
        const charts = readJson<StoredChart[]>(chartsKey, []);
        const chart = charts.find((item) => String(item.id) === String(chartId));
        return chart
            ? Promise.resolve(chart.content)
            : Promise.reject(new Error('Chart not found'));
    },

    getAllStudyTemplates(): Promise<any[]> {
        const templates = readJson<StoredStudyTemplate[]>(studyTemplatesKey, []);
        return Promise.resolve(templates.map(({ name }) => ({ name })));
    },

    removeStudyTemplate(studyTemplateInfo: { name: string }): Promise<void> {
        const templates = readJson<StoredStudyTemplate[]>(studyTemplatesKey, []);
        writeJson(
            studyTemplatesKey,
            templates.filter((template) => template.name !== studyTemplateInfo.name),
        );
        return Promise.resolve();
    },

    saveStudyTemplate(studyTemplateData: StoredStudyTemplate): Promise<void> {
        const templates = readJson<StoredStudyTemplate[]>(studyTemplatesKey, []);
        const nextTemplates = templates.filter(
            (template) => template.name !== studyTemplateData.name,
        );
        nextTemplates.push(studyTemplateData);
        writeJson(studyTemplatesKey, nextTemplates);
        return Promise.resolve();
    },

    getStudyTemplateContent(studyTemplateInfo: { name: string }): Promise<string> {
        const templates = readJson<StoredStudyTemplate[]>(studyTemplatesKey, []);
        const template = templates.find((item) => item.name === studyTemplateInfo.name);
        return template
            ? Promise.resolve(template.content)
            : Promise.reject(new Error('Study template not found'));
    },

    getDrawingTemplates(toolName: string): Promise<string[]> {
        const templates = readJson<StoredDrawingTemplate[]>(drawingTemplatesKey, []);
        return Promise.resolve(
            templates.filter((template) => template.toolName === toolName).map(({ name }) => name),
        );
    },

    loadDrawingTemplate(toolName: string, templateName: string): Promise<string> {
        const templates = readJson<StoredDrawingTemplate[]>(drawingTemplatesKey, []);
        const template = templates.find(
            (item) => item.toolName === toolName && item.name === templateName,
        );
        return template
            ? Promise.resolve(template.content)
            : Promise.reject(new Error('Drawing template not found'));
    },

    removeDrawingTemplate(toolName: string, templateName: string): Promise<void> {
        const templates = readJson<StoredDrawingTemplate[]>(drawingTemplatesKey, []);
        writeJson(
            drawingTemplatesKey,
            templates.filter(
                (template) =>
                    !(template.toolName === toolName && template.name === templateName),
            ),
        );
        return Promise.resolve();
    },

    saveDrawingTemplate(
        toolName: string,
        templateName: string,
        content: string,
    ): Promise<void> {
        const templates = readJson<StoredDrawingTemplate[]>(drawingTemplatesKey, []);
        const nextTemplates = templates.filter(
            (template) => !(template.toolName === toolName && template.name === templateName),
        );
        nextTemplates.push({ toolName, name: templateName, content });
        writeJson(drawingTemplatesKey, nextTemplates);
        return Promise.resolve();
    },

    getAllChartTemplates(): Promise<string[]> {
        const templates = readJson<Record<string, any>>(chartTemplatesKey, {});
        return Promise.resolve(Object.keys(templates));
    },

    getChartTemplateContent(templateName: string): Promise<any> {
        const templates = readJson<Record<string, any>>(chartTemplatesKey, {});
        return templates[templateName]
            ? Promise.resolve({ content: templates[templateName] })
            : Promise.reject(new Error('Chart template not found'));
    },

    saveChartTemplate(newName: string, theme: any): Promise<void> {
        const templates = readJson<Record<string, any>>(chartTemplatesKey, {});
        templates[newName] = theme;
        writeJson(chartTemplatesKey, templates);
        return Promise.resolve();
    },

    removeChartTemplate(templateName: string): Promise<void> {
        const templates = readJson<Record<string, any>>(chartTemplatesKey, {});
        delete templates[templateName];
        writeJson(chartTemplatesKey, templates);
        return Promise.resolve();
    },

    saveLineToolsAndGroups(
        layoutId: string | undefined,
        chartId: string | number,
        state: any,
    ): Promise<void> {
        const all = readJson<Record<string, any>>(lineToolsKey, {});
        all[`${layoutId ?? 'default'}_${chartId}`] = state;
        writeJson(lineToolsKey, all);
        return Promise.resolve();
    },

    loadLineToolsAndGroups(
        layoutId: string | undefined,
        chartId: string | number,
        _requestType?: any,
        _requestContext?: any,
    ): Promise<any> {
        const all = readJson<Record<string, any>>(lineToolsKey, {});
        return Promise.resolve(all[`${layoutId ?? 'default'}_${chartId}`] ?? null);
    },
});
